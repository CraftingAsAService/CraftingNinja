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

		$nodeTimers = $this->readTSV($this->manualDataLocation . '/nodeTimers.tsv');

		foreach ($nodeTimers as $entry)
			$this->setData('details', [
				'detailable_id'   => $entry['node_id'],
				'detailable_type' => 'node', // See Relation::morphMap in AppServiceProvider
				'data'            => json_encode([
					'hours'  => collect(explode(',', $entry['times']))->map(function($entry) use ($timeConverter) {
									return $timeConverter[trim($entry)] ?? $entry;
								})->implode(', '),
					'uptime' => $entry['uptime'] . 'm',
					'type'   => $entry['type'],
				]),
			]);
	}

	public function manualRandomVentureItems()
	{
		// Random Venture Items file is manually built
		$randomVentureItems = $this->readTSV($this->manualDataLocation . '/randomVentureItems.tsv')
			->pluck('items', 'venture');

		$objectiveTranslations = collect($this->data['objective_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('name')->map(function($entry) {
			return $entry['objective_id'];
		});

		$itemTranslations = collect($this->data['item_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('name')->map(function($entry) {
			return $entry['item_id'];
		});

		// The `venture` column should match against an objective name
		//  Likewise, exploding the `items` column on a comma, then looping those against the item names should produce a match
		//  And voila, entries to item_objective are born
		foreach ($randomVentureItems as $venture => $ventureItems)
		{
			$ventureItems = explode(',', str_replace(', ', ',', $ventureItems));
			// There's a one in X chance that the item is given
			//  Simplified and probably wrong, but it'll do
			$rate = number_format(1 / count($ventureItems), 2) * 100;

			foreach ($ventureItems as $itemName)
				if (isset($itemTranslations[$itemName]) && isset($objectiveTranslations[$venture]))
					$this->setData('item_objective', [
						'item_id'      => $itemTranslations[$itemName],
						'objective_id' => $objectiveTranslations[$venture],
						'reward'       => 1,
						'quantity'     => 1,
						'quality'      => 0,
						'rate'         => $rate
					]);
		}
	}

}
