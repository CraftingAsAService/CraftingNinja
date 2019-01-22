<?php

/**
 * Run `composer dump-autoload` when adding new seeders in this folder
 *
 * php artisan db:seed --class FFXIVSeeder --database ffxiv
 */

use Illuminate\Database\Eloquent\Model;

class FfxivSeeder extends GenericDataSeeder
{

	protected $dataLocation = null;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach ($this->seedData as $table)
			$this->seed($table);

		echo PHP_EOL . "Finished" . PHP_EOL;
	}

	private function seed($table)
	{
		$data = json_decode(file_get_contents($this->dataLocation . camel_case($table) . '.json'), true);
		$columns = array_shift($data);

		echo 'Inserting data into ' . $table . PHP_EOL;

		DB::statement('TRUNCATE `' . $table . '`');

		$this->batchInsert($table, $columns, $data);
	}

	private function batchInsert($table, $columns, $data)
	{
		$data = array_chunk($data, 300);
		$lastKey = count($data);

		foreach ($data as $batchId => $rows)
		{
			$values = $pdo = [];
			foreach ($rows as $row)
			{
				$values[] = '(' . str_pad('', count($row) * 2 - 1, '?,') . ')';

				// Cleanup value, if FALSE set to NULL
				foreach ($row as $value)
					$pdo[] = $value === false ? null : $value;
			}

			$keys = ' (`' . implode('`,`', $columns) . '`)';

			DB::insert('INSERT IGNORE INTO ' . $table . $keys . ' VALUES ' . implode(',', $values), $pdo);

			// Only update progress every 5 steps
			$this->progress($batchId + 1, $lastKey);
		}

		$this->progress($batchId + 1, $lastKey, true);
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
