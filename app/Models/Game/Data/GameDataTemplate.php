<?php

namespace App\Models\Game\Data;

abstract class GameDataTemplate
{
	public  $slug = '',
			$originDataLocation = null,
			$map = [],
			$runList = [];

	public function __construct()
	{
		$this->slug = strtolower((new \ReflectionClass($this))->getShortName());

		$this->originDataLocation = env('DATA_REPOSITORY') . '/' . $this->slug . '/';

		$this->loadMaps();

		$this->run();
	}

	/**
	 * Common Functions to all Game Data Parsing
	 */

	protected function run()
	{
		foreach ($this->runList as $run)
		{
			if (\Cache::has($this->slug . '-' . $run))
			{
				echo 'Skipping ' . $run . PHP_EOL;
				continue;
			}

			echo 'Starting ' . $run . PHP_EOL;

			clock()->startEvent($run, $run);

			$this->$run();

			$timeline = clock()->endEvent($run);

			$duration = round(clock()->getTimeline()->toArray()[$run]['duration'] / 1000, 2);
			$memoryUsage = $this->humanReadable(memory_get_usage());
			echo PHP_EOL . $run . ' ⧖ ' . $duration . 's, ' . $memoryUsage . PHP_EOL . PHP_EOL;

			\Cache::put($this->slug . '-' . $run, true, 10080); // Store for 1 week
		}
	}

	protected function humanReadable($size)
	{
		$fileSizeNames = [" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB"];
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .$fileSizeNames[$i] : '0 Bytes';
	}

	protected function write($filename, $array, $isMap = false)
	{
		$writeDir = $this->originDataLocation . 'parsed/' . ($isMap ? 'mappings/' : '');
		$array = $this->deduplicate($array);
		echo 'Writing ' . $filename . '.json - ' . (count($array) - 1)  . ' records' . PHP_EOL;
		file_put_contents($writeDir . $filename . '.json', json_encode($array));
	}

	protected function loadMaps()
	{
		foreach (array_diff(scandir($this->originDataLocation . 'parsed/mappings'), ['.', '..']) as $filename)
		{
			$action = str_replace('.json', '', $filename);
			$this->readMap($action);
		}
	}

	protected function readMap($filename)
	{
		$readDir = $this->originDataLocation . 'parsed/mappings/';
		$file = $readDir . $filename . '.json';
		if (is_file($file))
			$this->map[$filename] = json_decode(file_get_contents($file), true);
	}

	protected function saveMap($action, $data)
	{
		$this->write($action, $data, true);
		$this->map[$action] = $data;
	}

	protected function deduplicate($array)
	{
		return array_map('unserialize', array_unique(array_map('serialize', $array)));
	}

	protected function scanDir($dir)
	{
		$filesList = array_diff(scandir($dir), ['.', '..']);
		natsort($filesList);
		return array_values($filesList);
	}

	protected function grepDir($dir, $pattern)
	{
		echo 'Grepping ' . $dir . PHP_EOL;
		exec('grep -rl \'' . $dir . '\' -e \'' . $pattern . '\'', $filesList);
		natsort($filesList);
		return array_values($filesList);
	}

	protected function clean($string)
	{
		// Further deconvert some characters
		$string = str_replace('–', '-', $string);
		$string = str_replace("\r\n", ' ', $string);
		$string = preg_replace('/\<\/?Emphasis\>/', '', $string);
		$string = str_replace('<SoftHyphen/>', '-', $string);

		return $string;
	}

	protected function getJSON($path)
	{
		$content = file_get_contents($path);

		// http://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok
		for ($i = 0; $i <= 31; ++$i)
			$content = str_replace(chr($i), "", $content);
		$content = str_replace(chr(127), "", $content);

		// This is the most common part
		$content = $this->binaryFix($content);

		$content = json_decode($content, true);

		return $content;
	}

	protected function binaryFix($string)
	{
		// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
		// here we detect it and we remove it, basically it's the first 3 characters
		if (0 === strpos(bin2hex($string), 'efbbbf'))
			$string = substr($string, 3);

		return $string;
	}

	protected $_spinners = ['|', '/', '-', '\\', '|', '/', '-', '\\'];

	/**
	 * This is used to echo the progress of a task on the command line.
	 * Pass in the current row that you are on and the number of rows that need to be processed and this will echo out
	 * a progress bar like this
	 *
	 * Progress: [-----------------\                                           ]
	 *
	 * It is possible to change the width of the bar by passing in an int as the $steps param, otherwise this default
	 * to 60
	 *
	 * Once the process is complete pass in the $last param as true to finish the the process bar
	 *
	 * @param      $totalDone - The number of rows that have been processed so far
	 * @param      $total     - The total number of rows to be processed
	 * @param bool $last      - If the process has been completed
	 * @param bool $steps     - How wide the process bar should be
	 */
	public function progress($totalDone, $total, $last = false, $steps = false)
	{
		if (PHP_SAPI != 'cli')
			return;

		$steps = $steps == false ? 40 : $steps;
		if ($last === true)
			$display = "Progress: [" . str_repeat('-', $steps + 1) . "]\r" . PHP_EOL;
		else {
			$toGo        = floor((1 - ($totalDone / $total)) * $steps);
			$progressBar = str_repeat('-', $steps - $toGo);
			$emptySpace  = str_repeat(' ', $toGo);
			$index       = $totalDone % 8;
			$display     = "Progress: [" . $progressBar . $this->_spinners[$index] . $emptySpace . "]\r";
		}

		echo $display;
	}

}
