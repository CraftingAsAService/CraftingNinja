<?php

/**
 * Osmose
 * 	This spell absorbs DATA from Aspir
 * 	Based on existing CSV files, data is imported
 */

namespace App\Models\Aspir;

use App\Traits\BatchInsert;
use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Osmose
{

	use BatchInsert;

	public function __construct(&$command, $gameSlug)
	{
		set_time_limit(0);
		Model::unguard();
		DB::connection()->disableQueryLog();
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->command =& $command;

		$this->gameSlug = $gameSlug;
	}

	public function __destruct()
	{
		// Cleanup
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

	public function run()
	{
		// For now, I trust that the `aspir` process preserves IDs long term
		//  User slings (carts) will reference item IDs, and need to stay the same
		foreach (array_merge(config('aspir.pivotTables'), config('aspir.entityTables')) as $tableName)
		{
			// $data = $this->getData($tableName);
			$dataFile = 'game-data/' . $this->gameSlug . '/' . $tableName . '.csv';

			if ( ! Storage::exists($dataFile))
				continue;

			$columns = str_getcsv(fgets(fopen(Storage::path($dataFile), 'r')));

			$this->command->info('Inserting data into ' . $tableName);

			dd($columns);

			// $this->truncate($tableName);

			// LOAD DATA INFILE '/tmp/test.txt' REPLACE INTO TABLE test (col1, col2)
			// IGNORE 1 LINES;

			// $this->batchInsert($data, $tableName, $this->gameSlug);
		}
	}

	// private function getData($tableName)
	// {
	// 	return json_decode(Storage::get('game-data/' . $this->gameSlug . '/' . $tableName . '.json'), true);
	// }

	private function truncate($tableName)
	{
		DB::connection($this->gameSlug)->table($tableName)->truncate();
	}

}
