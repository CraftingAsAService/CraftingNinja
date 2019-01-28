<?php

// php artisan db:seed --class FfxivGameSeeder --database ffxiv

class FfxivGameSeeder extends GenericDataSeeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$coordinatesList = glob($this->dataLocation . '*_coordinates.json');

		$this->combinationSeed('coordinates', $coordinatesList);

		$detailsList = glob($this->dataLocation . '*_details.json');

		$this->combinationSeed('details', $detailsList);

		$seedList = array_diff(glob($this->dataLocation . '*.json'), $coordinatesList, $detailsList);

		foreach ($seedList as $seedFile)
			$this->seed($seedFile);

		echo PHP_EOL . "Finished" . PHP_EOL;
	}

	private function seed($seedFile)
	{
		$table = pathinfo($seedFile)['filename'];
		$data = json_decode(file_get_contents($seedFile), true);
		$columns = array_shift($data);

		echo 'Inserting data into ' . $table . PHP_EOL;

		DB::statement('TRUNCATE `' . $table . '`');

		$this->batchInsert($table, $columns, $data);
	}

	private function combinationSeed($table, $seedList)
	{
		echo 'Inserting data into ' . $table . PHP_EOL;

		DB::statement('TRUNCATE `' . $table . '`');

		foreach ($seedList as $seedFile)
		{
			$data = json_decode(file_get_contents($seedFile), true);

			$columns = array_shift($data);

			$this->batchInsert($table, $columns, $data);
		}
	}

}
