<?php

namespace App\Traits;

use DB;

trait BatchInsert
{

	/**
	 * Use PDO to batch insert data
	 * @param  array   $originalData The data to insert into the table
	 *                                Should be passed in a basic [ [ 'columnName' => 'columnValue' ], ] manner
	 * @param  string  $table        The table to insert the data into
	 * @param  string  $connection   `null`/`false`/'' for default connection; DB::connection('') uses default
	 * @param  int     $chunkSize    How to split up array_chunk
	 * @return array                 Returns an empty array
	 */
	private function batchInsert($originalData = [], $table = '', $connection = null, $chunkSize = 50)
	{
		if ( ! $table)
			return [];

		$originalData = array_chunk($originalData, $chunkSize);

		foreach ($originalData as $key => $data)
		{
			unset($originalData[$key]); // Memory Management

			$values = $insertData = [];
			foreach ($data as $row)
			{
				// Generate `(?,?,...?)` for PDO insertion
				$values[] = '(' . str_pad('', count($row) * 2 - 1, '?,') . ')';

				foreach ($row as $value)
					$insertData[] = $value;
			}

			DB::connection($connection)->insert(
				'INSERT IGNORE INTO ' . $table . ' ' .
				'(`' . implode('`,`', array_keys($data[0])) . '`) ' .
				'VALUES ' . implode(',', $values)
			, $insertData);
		}

		return [];
	}

}
