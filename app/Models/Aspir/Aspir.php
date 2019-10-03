<?php

/**
 * Aspir
 * 	This spell absorbs DATA from ANYWHERE
 * 	By scanning multiple sources of data, it builds CSV files matching database structure
 *
 * WARNING - PUBLIC METHODS ARE RUN IN ORDER
 *  Methods are pulled using get_class_methods(),
 *  then their type is filtered with ReflectionMethod to test if it's public
 * Exception: __construct() and run() are required to be public, and are excluded manually
 */

namespace App\Models\Aspir;

abstract class Aspir
{

	public function __construct(&$command)
	{
		set_time_limit(0);

		$this->command =& $command;

		$this->gameSlug = strtolower((new \ReflectionClass($this))->getShortName());

		$this->manualDataLocation = base_path('../data/' . $this->gameSlug);

		$this->data = collect([]);
		// $this->data = collect(config('aspir.dataTemplate'))->map(function($array) {
		// 	return collect($array);
		// });

		$this->methodList = collect(get_class_methods($this))->diff(['__construct', 'run'])->filter(function($methodName) {
			return (new \ReflectionMethod($this, $methodName))->isPublic();
		});
	}

	public function run()
	{
		$rowCounts = $this->getRowCounts(false);

		foreach ($this->methodList as $function)
		{
			$beginningRowCounts = $rowCounts;

			$this->$function();

			$rowCounts = $this->getRowCounts(false);

			foreach ($rowCounts->diff($beginningRowCounts) as $dataPoint => $count)
				$this->command->info($dataPoint . ' now has ' . $count . ' rows');
		}

		$this->saveData();
	}

	/**
	 * Array Handling Functions
	 */

	protected function getRowCounts($reduce = true)
	{
		return $this->data->map(function($collection) {
				return $collection->count();
			})->filter(function($amount) use ($reduce) {
				return $reduce ? $amount > 0 : true;
			});
	}

	protected function setData($table, $row, $id = null)
	{
		// If id is null, use the length of the existing data, or check in the $row for it
		$id = $id ?: (isset($row['id']) ? $row['id'] : count($this->data[$table]) + 1);

		if (isset($this->data[$table][$id]))
			$this->data[$table][$id] = array_merge($this->data[$table][$id], $row);
		else
			$this->data[$table][$id] = $row;

		return $id;
	}

	/**
	 * File Saving Functions
	 */

	protected function saveData()
	{
		$this->command->comment('Saving Data');

		foreach ($this->data as $filename => $data)
			$this->writeToJSON($filename, $data);
	}

	protected function writeToJSON($filename, $list)
	{
		if (empty($list))
			$this->command->comment('No data for ' . $filename);
		else
			$this->command->info('Saving ' . count($list) . ' records to ' . $filename . '.json');

		\Storage::put('app/game-data/' . $this->gameSlug . '/' . $filename . '.json', json_encode($list, JSON_PRETTY_PRINT));
		// file_put_contents(storage_path(''), json_encode($list, JSON_PRETTY_PRINT));
	}

}
