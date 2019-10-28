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

	public function __construct(&$command)
	{
		set_time_limit(0);

		$this->command =& $command;

		$this->gameSlug = strtolower((new \ReflectionClass($this))->getShortName());

		$this->tables = array_keys(config('aspir.dataTemplate'));

		Model::unguard();
		DB::connection()->disableQueryLog();
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	}

	public function __deconstruct()
	{
		// Cleanup
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

	public function run()
	{
		foreach ($this->tables as $tableName)
		{
			$data = json_decode(Storage::get('game-data/' . $this->gameSlug . '/' . $tableName . '.json'), true);
			DB::connection($this->gameSlug)->table($tableName)->truncate();
			$this->batchInsert($data, $tableName, $this->gameSlug);
		}
	}

}
