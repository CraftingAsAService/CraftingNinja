<?php

/**
 * ManualData
 * 	Manually parsed data; by hand
 */

namespace App\Models\Aspir\Ffxiv;

trait ManualData
{

	public function manualNodeCoordinates()
	{
		// GatheringPointName Conversions, for coordinate matching
		$typeConverter = [
			'Mineral Deposit'       => 0, // via Mining
			'Rocky Outcrop'         => 1, // via Quarrying
			'Mature Tree'           => 2, // via Logging
			'Lush Vegetation Patch' => 3, // via Harvesting
		];

		$zoneTranslations = collect($this->data['zone_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('name')->map(function($entry) {
			return $entry['zone_id'];
		});

		$nodes = collect($this->data['nodes']);

		// Node Coordinates file is manually built
		$manualCoordinateData = $this->readTSV($this->manualDataLocation . '/nodeCoordinates.tsv');

		$coordinates = collect($this->data['coordinates']);

		foreach ($manualCoordinateData as $data)
		{
			$zoneId = $zoneTranslations[$data['location']] ?? 0;

			if ( ! $zoneId)
				continue;

			$coords = explode(' x ', $data['coordinates']);

			$possibleNodes = $nodes->where('level', $data['level'])
				->where('type', $typeConverter[$data['type']])
				->pluck('id');

			$coordinates->where('zone_id', $zoneId)
				->where('coordinate_type', 'node')
				->whereIn('coordinate_id', $possibleNodes)
				->map(function($entry) use ($coords) {
					$entry['x'] = $coords[0];
					$entry['y'] = $coords[1];
					return $entry;
				});
		}

		$this->data['coordinates'] = $coordinates->toArray();
	}

	public function manualNodeTimers()
	{
		$timeConverter = [
			 0 =>  'Midnight',
			 1 =>  '1am',
			 2 =>  '2am',
			 3 =>  '3am',
			 4 =>  '4am',
			 5 =>  '5am',
			 6 =>  '6am',
			 7 =>  '7am',
			 8 =>  '8am',
			 9 =>  '9am',
			10 => '10am',
			11 => '11am',
			12 => 'Noon',
			13 =>  '1pm',
			14 =>  '2pm',
			15 =>  '3pm',
			16 =>  '4pm',
			17 =>  '5pm',
			18 =>  '6pm',
			19 =>  '7pm',
			20 =>  '8pm',
			21 =>  '9pm',
			22 => '10pm',
			23 => '11pm',
		];

		dd('TODO TIMERS');

		$nodeTimers = $this->readTSV($this->path . 'nodeTimers.tsv')
			->mapWithKeys(function($record) use ($timeConverter) {
				$hours = collect(explode(',', $record['times']))->map(function($entry) use ($timeConverter) {
					return $timeConverter[trim($entry)] ?? $entry;
				})->implode(', ');
				return [ $record['node_id'] => $hours . ' for ' . $record['uptime'] . 'm' ];
			});

		foreach ($nodeTimers as $nodeId => $timer)
			if (isset($this->data['node'][$nodeId]))
				$this->data['node'][$nodeId]['timer'] = $timer;
	}

	public function manualRandomVentureItems()
	{
		// Random Venture Items file is manually built
		$randomVentureItems = $this->readTSV($this->path . 'randomVentureItems.tsv')
			->pluck('items', 'venture');

		$ventures = collect($this->data['venture'])->pluck('id', 'name')->toArray();
		$items = collect($this->data['item'])->pluck('id', 'name')->toArray();

		// The `venture` column should match against a `venture.name`
		//  Likewise, exploding the `items` column on a comma, then looping those against the `item.name` should produce a match
		//  And voila, populate `item_venture`
		foreach ($randomVentureItems as $venture => $ventureItems)
		{
			$ventureItems = explode(',', str_replace(', ', ',', $ventureItems));

			foreach ($ventureItems as $itemName)
				if (isset($items[$itemName]) && isset($ventures[$venture]))
					$this->setData('item_venture', [
						'venture_id' => $ventures[$venture],
						'item_id'    => $items[$itemName],
					]);
		}
	}

	public function manualLeveTypes()
	{
		$leveTypes = $this->readTSV($this->path . 'leveTypes.tsv')
			->pluck('type', 'plate');

		foreach ($this->data['leve'] as &$leve)
			if (isset($leveTypes[$leve['plate']]))
				$leve['type'] = $leveTypes[$leve['plate']];
	}


}
