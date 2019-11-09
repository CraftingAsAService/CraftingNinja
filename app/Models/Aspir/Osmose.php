<?php

/**
 * Osmose
 * 	This spell absorbs DATA from Aspir
 * 	Based on existing CSV files, data is imported
 *
 * Required Configuration to my.cnf (Only doing this to local vagrant instance)
 * [mysqld]
 * local_infile = 1
 * secure-file-priv = ""
 */

namespace App\Models\Aspir;

use Illuminate\Database\Eloquent\Model;
use DB;
use Storage;

class Osmose
{

	public function __construct(&$command, $gameSlug)
	{
		set_time_limit(0);
		// Model::unguard();
		// DB::connection()->disableQueryLog();
		// DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->command =& $command;

		$this->gameSlug = $gameSlug;
	}

	public function __destruct()
	{
		// Cleanup
		// DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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

			DB::connection($this->gameSlug)->table($tableName)->truncate();

			DB::connection($this->gameSlug)->getpdo()->exec(
				'SET FOREIGN_KEY_CHECKS=0'
			);
			DB::connection($this->gameSlug)->getpdo()->exec(
				'LOAD DATA LOCAL INFILE \'' . Storage::path($dataFile) . '\' ' .
				'REPLACE INTO TABLE `' . $tableName . '` ' .
				'CHARACTER SET utf8 ' .
				'FIELDS TERMINATED BY \',\' ENCLOSED BY \'"\' ' .
				'IGNORE 1 LINES ' .
				'(`' . implode('`, `', $columns) . '`)'
			);
		}
	}

}
