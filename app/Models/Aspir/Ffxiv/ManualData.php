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

		$nodeTranslations = collect($this->data['node_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('name')->map(function($entry) {
			return $entry['node_id'];
		});

		// Node Coordinates file is manually built
		$coordinates = $this->readTSV($this->manualDataLocation . '/nodeCoordinates.tsv');

		$coordinates = $coordinates->map(function($row) use ($typeConverter, $nodeTranslations) {
			$row['type'] = $typeConverter[$row['type']];
			$coords = explode(' x ', $row['coordinates']);
			$row['x'] = $coords[0];
			$row['y'] = $coords[1];
			$row['zone_id'] = $nodeTranslations[$row['location']] ?? 0;
			return $row;
		})/*->filter(function($row) {
			return $row['zone_id'] !== 0;
		})*/;

		dd($coordinates);


		// $areaFinder = collect($this->data['nodes'])->map(function($entry) use ($nodeTranslations) {
		// 	return
		// })


		foreach ($this->data['coordinates'] as &$coordinate)
			if ($coordinate[''])
		foreach ($this->data['nodes'] as &$node)
			$node['coordinates'] = isset($areaFinder[$node['zone_id']]) && isset($typeConverter[$node['type']])
				? $coordinates
					->where('location', $areaFinder[$node['zone_id']])
					->where('level', $node['level'])
					->where('type', $typeConverter[$node['type']])
					->pluck('coordinates')->join(', ', ' or ')
				: null;
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
