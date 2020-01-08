<?php

namespace App\Traits;

trait ProgressBar
{

	protected $spinners = ['|', '/', '-', '\\', '|', '/', '-', '\\'];

	/**
	 * This is used to echo the progress of a task on the command line.
	 * Pass in the current row that you are on and the number of rows that need to be processed
	 *  and this will echo out a progress bar like this
	 *
	 * Progress: [-----------------\                                           ]
	 *
	 * It is possible to change the width of the bar by passing in an int as the $steps param,
	 *  otherwise this default to 40
	 *
	 * Once the process is complete pass in the $last param as true to finish the the progress bar
	 *
	 * @param      $totalDone - The number of rows that have been processed so far
	 * @param      $total     - The total number of rows to be processed
	 * @param bool $last      - If the process has been completed
	 * @param int  $steps     - How wide the process bar should be
	 */
	protected function progress($totalDone, $total, $last = false, $steps = 40)
	{
		if (PHP_SAPI != 'cli')
			return;

		if ($last === true)
			$display = "Progress: [" . str_repeat('-', $steps + 1) . "]\r" . PHP_EOL;
		else
		{
			$toGo        = floor((1 - ($totalDone / $total)) * $steps);
			$progressBar = str_repeat('-', $steps - $toGo);
			$emptySpace  = str_repeat(' ', $toGo);
			$index       = $totalDone % 8;
			$display     = "Progress: [" . $progressBar . $this->spinners[$index] . $emptySpace . "]\r";
		}

		echo $display;
	}

}
