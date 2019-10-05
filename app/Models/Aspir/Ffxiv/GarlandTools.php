<?php

/**
 * GarlandTools
 * 	Parse data manually provided from GarlandTools
 * 	Legitimate data ends with v5.0, but historically it should be accurate for a good while
 */

namespace App\Models\Aspir\Ffxiv;

trait GarlandTools
{


	/**
	 * Helper Functions
	 */

	private function translateMobID($mobId, $base = false)
	{
		// The mob id can be split between base and name
		if ($base)
			return (int) ($mobId / 10000000000);
		return (int) ($mobId % 10000000000);
	}

	private function loopGarlandEndpoint($endpoint, $callback)
	{
		foreach ($this->getFileList($endpoint) as $file)
			$callback($this->getJSONData($file, $endpoint));
	}

	private function getFileList($endpoint, $language = 'en')
	{
		return array_diff(scandir($this->path . $language . '/' . $endpoint), ['.', '..']);
	}

	private function getJSONData($filename, $endpoint, $language = 'en')
	{
		$file = $this->path . $language . '/' . $endpoint . '/' . $filename;
		return $this->getCleanedJson($file);
	}

}
