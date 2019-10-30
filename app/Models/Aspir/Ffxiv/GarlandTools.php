<?php

/**
 * GarlandTools
 * 	Parse data manually provided from GarlandTools
 * 	Legitimate data ends with v5.0, but historically it should be accurate for a good while
 */

namespace App\Models\Aspir\Ffxiv;

trait GarlandTools
{

	public function garlandMobs()
	{
		$this->loopGarlandEndpoint('mob', function($data) {
			$mobId = $this->translateMobID($data->mob->id);

			$this->setData('mob', [
				'level'   => $data->mob->lvl,
			], $mobId, true);

			$this->setData('coordinates', [
				'zone_id'         => $data->mob->zoneid,
				'coordinate_id'   => $mobId,
				'coordinate_type' => 'npc', // See Relation::morphMap in AppServiceProvider
				'x'               => null,
				'y'               => null,
				'z'               => null,
				'radius'          => null,
			]);

			// And now for dropped items
			foreach ($data->mob->drops as $itemId)
				$this->setData('item_npc', [
					'item_id' => $itemId,
					'mob_id'  => $mobId,
				]);
		});
	}

	public function garlandNpcs()
	{
		$this->loopGarlandEndpoint('npc', function($data) {
			if ( ! (isset($data->npc->zoneid) && $data->npc->zoneid))
				return;

			$this->setData('coordinates', [
				'zone_id'         => $data->npc->zoneid,
				'coordinate_id'   => $data->npc->id,
				'coordinate_type' => 'npc', // See Relation::morphMap in AppServiceProvider
				'x'               => $data->npc->coords[0] ?? null,
				'y'               => $data->npc->coords[1] ?? null,
				'z'               => null,
				'radius'          => null,
			]);
		});
	}

	public function garlandInstances()
	{
		$this->loopGarlandEndpoint('instance', function($data) {
			$itemIds = [];

			if (isset($data->instance->fights))
				foreach ($data->instance->fights as $f)
				{
					if (isset($f->coffer))
						$itemIds = array_merge($itemIds, $f->coffer->items);

					foreach ($f->mobs as $mobID)
						$this->setData('coordinates', [
							'zone_id'         => $data->instance->id,
							'coordinate_id'   => $this->translateMobID($mobID),
							'coordinate_type' => 'npc', // See Relation::morphMap in AppServiceProvider
							'x'               => null,
							'y'               => null,
							'z'               => null,
							'radius'          => null,
						]);
				}

			if (isset($data->instance->rewards))
				$itemIds = array_merge($itemIds, $data->instance->rewards);

			if (isset($data->instance->coffers))
				foreach ($data->instance->coffers as $coffer)
					$itemIds = array_merge($itemIds, $coffer->items);

			foreach ($itemIds as $itemId)
				$this->setData('coordinates', [
					'zone_id'         => $data->instance->id,
					'coordinate_id'   => $itemId,
					'coordinate_type' => 'item', // See Relation::morphMap in AppServiceProvider
					'x'               => null,
					'y'               => null,
					'z'               => null,
					'radius'          => null,
				]);
		});
	}

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
		return array_diff(scandir($this->manualDataLocation . '/garlandTools/' . $language . '/' . $endpoint), ['.', '..']);
	}

	private function getJSONData($filename, $endpoint, $language = 'en')
	{
		return $this->getCleanedJson($this->manualDataLocation . '/garlandTools/' . $language . '/' . $endpoint . '/' . $filename);
	}

}
