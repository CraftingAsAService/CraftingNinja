<?php

/**
 * XIVAPI
 * 	Get data from XIVAPI
 */

namespace App\Models\Aspir\Ffxiv;

use Cache;

trait XIVAPI
{

	public $xivapiLanguages = ['en', 'de', 'fr', 'ja'];

	public $limit = null;
	public $chunkLimit = null;

	public function xivapiSetup()
	{
		$this->api = new \XIVAPI\XIVAPI();
		$this->api->environment->key(config('services.xivapi.key'));
	}

	public function objectives()
	{
		// Achievement IDs range from 1 to less than 2400+; ALLOTMENT 1 to 20k
		$this->achievements(0);
		// Fate IDs range from 1 to 1500+; ALLOTMENT 20,001 to 40k
		$this->fates(20000);
		// Leve IDs range from 1 to 1500+; ALLOTMENT 40,001 to 60k
		$this->leves(40000);
		// Quest IDs range from 65500+ to ~70k
		$this->quests(0);
	}

	protected function achievements($idAdditive)
	{
		$achievementTypeId = array_search('achievement', config('games.ffxiv.objectiveTypes'));

		if ($achievementTypeId === false)
			$this->error('Could not find Objective Type ID for "achievement"');

		$apiFields = [
			'ID',
			'ItemTargetID',
			'IconID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'Description_%s');

		$this->loopEndpoint('achievement', $apiFields, function($data) use ($idAdditive, $achievementTypeId) {
			// We only care about achievements that provide an item
			if ( ! $data->ItemTargetID)
				return;

			$objectiveId = $data->ID + $idAdditive;

			$this->setData('objectives', [
				'id'         => $objectiveId,
				'niche_id'   => null,
				'issuer_id'  => null,
				'target_id'  => null,
				'type'       => $achievementTypeId,
				'repeatable' => null,
				'level'      => null,
				'icon'       => $data->IconID,
			], $objectiveId);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('objective_translations', [
					'objective_id' => $objectiveId,
					'locale'       => $lang,
					'name'         => $data->{'Name_' . $lang} ?? null,
					'description'  => $data->{'Description_' . $lang} ?? null,
				]);

			$this->setData('item_objective', [
				'item_id'      => $data->ItemTargetID,
				'objective_id' => $objectiveId,
				'reward'       => 1,
				'quantity'     => null,
				'quality'      => null,
				'rate'         => null,
			]);

			/**
			 * Achievements don't have coordinates
			 */
		});
	}

	protected function fates($idAdditive)
	{
		/**
		 * Ignoring
		 * Fates don't offer any items of value
		 */
	}

	protected function leves($idAdditive)
	{
		$leveTypeId = array_search('leve', config('games.ffxiv.objectiveTypes'));

		if ($leveTypeId === false)
			$this->error('Could not find Objective Type ID for "leve"');

		// 3000 calls were taking over the allotted 10s call limit imposed by XIVAPI's Guzzle Implementation
		$this->limit = 1000;
		$this->chunkLimit = 10;

		$apiFields = [
			'ID',
			'ClassJobCategory.ID',
			'LeveClient.ID',
			'LeveVfx.IconID',
			'LeveVfxFrame.IconID',
			'GilReward',
			'ExpReward',
			'ClassJobLevel',
			'PlaceNameIssuedTargetID',
			'PlaceNameStartZoneTargetID',
			'IconIssuerID',
			'CraftLeve.Item0TargetID',
			'CraftLeve.ItemCount0',
			'CraftLeve.Repeats',
			// Inefficient catchall, but there are a large number of datapoints in there I need to sift through
			'LeveRewardItem',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'LeveClient.Name_%s');
		$this->addLanguageFields($apiFields, 'Description_%s');

		$this->loopEndpoint('leve', $apiFields, function($data) use ($idAdditive, $leveTypeId) {
			// No rewards? Don't bother.
			if ($data->LeveRewardItem == null)
				return;

			$objectiveId = $data->ID + $idAdditive;
			$targetId = $data->LeveClient->ID + $idAdditive;
			$issuerId = $targetId;

			// The issuer may be a Levemete, who is an NPC, but is treated more like a "place" than a "person"
			//  Not sure how to handle this yet, for now, marking the issuer null seems like the best way
			//  to indicate that something is up, and to use coordinates instead
			if ($data->PlaceNameIssuedTargetID != $data->PlaceNameStartZoneTargetID)
				$issuerId = null;

			$this->setData('objectives', [
				'id'         => $objectiveId,
				'niche_id'   => $data->ClassJobCategory->ID,
				'issuer_id'  => $issuerId,
				'target_id'  => $targetId,
				'type'       => $leveTypeId,
				'repeatable' => $data->CraftLeve->Repeats, // Only CraftLeves can repeat
				'level'      => $data->ClassJobLevel,
				'icon'       => 'leve',
			], $objectiveId);

			$this->setData('details', [
				'detailable_id'   => $objectiveId,
				'detailable_type' => 'objective', // See Relation::morphMap in AppServiceProvider
				'data'            => [
					'xp'    => $data->ExpReward,
					'gil'   => $data->GilReward,
					'plate' => $data->LeveVfx->IconID,
					'frame' => $data->LeveVfxFrame->IconID,
				]
			]);

			$this->setData('coordinates', [
				'zone_id'         => $data->PlaceNameIssuedTargetID,
				'coordinate_id'   => $objectiveId,
				'coordinate_type' => 'objective', // See Relation::morphMap in AppServiceProvider
				'x'               => null,
				'y'               => null,
				'z'               => null,
				'radius'          => null,
			]);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('objective_translations', [
					'objective_id' => $objectiveId,
					'locale'       => $lang,
					'name'         => $data->{'Name_' . $lang} ?? null,
					'description'  => $data->{'Description_' . $lang} ?? null,
				]);

			// Target NPC Creation
			$this->setData('npcs', [
				'id'    => $targetId,
				'enemy' => 0,
				'level' => null,
			], $targetId);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('npc_translations', [
					'npc_id' => $targetId,
					'locale' => $lang,
					'name'   => $data->LeveClient->{'Name_' . $lang} ?? null,
				]);

			// Requirements
			//  Up to slot 3 targets exist, however I couldn't find a use-case where a leve required more than one
			if ($data->CraftLeve->Item0TargetID)
				$this->setData('item_objective', [
					'item_id'      => $data->CraftLeve->Item0TargetID,
					'objective_id' => $objectiveId,
					'reward'       => 0,
					'quantity'     => $data->CraftLeve->ItemCount0,
					'quality'      => null,
					'rate'         => null,
				]);

			// Rewards
			//  Come in 8 total "Groups"
			//  Ignoring Crystals, there's too many to bother with, and it's not a particularly useful piece of information
			foreach (range(0, 7) as $slot)
			{
				$probability = $data->LeveRewardItem->{'Probability%' . $slot};

				if ( ! $probability)
					continue;

				$rewardGroup =& $data->LeveRewardItem->{'LeveRewardItemGroup' . $slot};

				// Items could be of a higher quality
				foreach (['Count' => 0, 'HQ' => 1] as $keyVerb => $quality)
					// Up to 9 total items can be in a group
					foreach (range(0, 8) as $itemSlot)
						// Count0/HQ0 should be higher than 0, Item0Target should be set to "Item", and the item shouldn't be a crystal
						//  Crystals are Category 59
						if ($rewardGroup->{$keyVerb . $itemSlot} && $rewardGroup->{'Item' . $itemSlot . 'Target'} == 'Item' && $rewardGroup->{'Item' . $itemSlot . 'TargetID'} && $rewardGroup->{'Item' . $itemSlot}->ItemUICategory != 59)
							$this->setData('item_objective', [
								'item_id'      => $rewardGroup->{'Item' . $itemSlot . 'TargetID'},
								'objective_id' => $objectiveId,
								'reward'       => 1,
								'quantity'     => $rewardGroup->{$keyVerb . $itemSlot},
								'quality'      => $quality,
								'rate'         => $probability,
							]);
			}
		});

		$this->chunkLimit = null;
		$this->limit = null;
	}

	protected function quests($idAdditive)
	{
		$questTypeId = array_search('quest', config('games.ffxiv.objectiveTypes'));

		if ($questTypeId === false)
			$this->error('Could not find Objective Type ID for "quest"');

		// 3000 calls were taking over the allotted 10s call limit imposed by XIVAPI's Guzzle Implementation
		$this->limit = 400;

		$apiFields = [
			'ID',
			'Name',
			'ClassJobCategory0TargetID',
			'ClassJobLevel0',
			'SortKey',
			'PlaceNameTargetID',
			'IconID',
			'IssuerStart',
			'TargetEnd',
			'JournalGenreTargetID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		// Required; There's like 40 of these, but I'm only going to go for 10
		$this->addNumberedFields($apiFields, 'ScriptInstruction%d', range(0, 9));
		$this->addNumberedFields($apiFields, 'ScriptArg%d', range(0, 9));
		// Rewards; 00-05 are guaranteed. 10-14 are choices. Catalysts are likely guaranteed as well.
		// 	Make sure the Target's are "Item"
		$this->addNumberedFields($apiFields, 'ItemReward0%d', range(0, 5));
		$this->addNumberedFields($apiFields, 'ItemCountReward0%d', range(0, 5));
		$this->addNumberedFields($apiFields, 'ItemReward1%d', range(0, 4));
		$this->addNumberedFields($apiFields, 'ItemCountReward1%d', range(0, 4));
		// Ignoring Crystals (aka Catalysts), there's too many to bother with, and it's not a particularly useful piece of information
		// $this->addNumberedFields($apiFields, 'ItemCatalyst%dTarget', range(0, 2));
		// $this->addNumberedFields($apiFields, 'ItemCatalyst%dTargetID', range(0, 2));
		// $this->addNumberedFields($apiFields, 'ItemCountCatalyst%d', range(0, 2));

		$this->loopEndpoint('quest', $apiFields, function($data) use ($idAdditive, $questTypeId) {
			// Skip empty names
			if ($data->Name == '')
				return;

			$objectiveId = $data->ID + $idAdditive;

			$this->setData('objectives', [
				'id'         => $objectiveId,
				'niche_id'   => $data->ClassJobCategory0TargetID,
				'issuer_id'  => $data->IssuerStart,
				'target_id'  => $data->TargetEnd,
				'type'       => $questTypeId,
				'repeatable' => 0,
				'level'      => $data->ClassJobLevel0,
				'icon'       => $data->IconID,
			], $objectiveId);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('objective_translations', [
					'objective_id' => $objectiveId,
					'locale'       => $lang,
					'name'         => $data->{'Name_' . $lang} ?? null,
					'description'  => null,
				]);

			$this->setData('coordinates', [
				'zone_id'         => $data->PlaceNameTargetID,
				'coordinate_id'   => $objectiveId,
				'coordinate_type' => 'objective', // See Relation::morphMap in AppServiceProvider
				'x'               => null,
				'y'               => null,
				'z'               => null,
				'radius'          => null,
			]);

			// Required Items
			foreach (range(0, 9) as $slot)
				if (substr($data->{'ScriptInstruction' . $slot}, 0, 5) == 'RITEM')
					$this->setData('item_objective', [
						'item_id'      => $data->{'ScriptArg' . $slot},
						'objective_id' => $objectiveId,
						'reward'       => 0,
						'quantity'     => 1,
						'quality'      => null,
						'rate'         => null,
					]);

			// Reward Items, Guaranteed, 00-05
			foreach (range(0, 5) as $slot)
				if ($data->{'ItemReward0' . $slot})
					$this->setData('item_objective', [
						'item_id'      => $data->{'ItemReward0' . $slot},
						'objective_id' => $objectiveId,
						'reward'       => 1,
						'quantity'     => $data->{'ItemCountReward0' . $slot},
						'quality'      => null,
						'rate'         => null,
					]);

			// Reward Items, Optional, 10-14
			foreach (range(0, 4) as $slot)
				if ($data->{'ItemReward1' . $slot . 'TargetID'} ?? false && $data->{'ItemReward1' . $slot . 'Target'} == 'Item')
					$this->setData('item_objective', [
						'item_id'      => $data->{'ItemReward1' . $slot . 'TargetID'},
						'objective_id' => $objectiveId,
						'reward'       => 1,
						'quantity'     => $data->{'ItemCountReward1' . $slot},
						'quality'      => null,
						'rate'         => null,
					]);
		});

		$this->limit = null;
	}

	public function zones()
	{
		$apiFields = [
			'ID',
			'Maps.0.PlaceNameRegionTargetID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');

		$this->loopEndpoint('placename', $apiFields, function($data) {
			// Skip empty english names
			if ($data->Name_en == '')
				return;

			$this->setData('zones', [
				'id'      => $data->ID,
				'zone_id' => $data->Maps[0]->PlaceNameRegionTargetID ?? null, // Parent Zone
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('zone_translations', [
					'zone_id' => $data->ID,
					'locale'  => $lang,
					'name'    => $data->{'Name_' . $lang} ?? null,
				]);
		});
	}

	public function nodes()
	{
		// Gathering Point Base IDs range from 1 to the mid 600s
		$this->gatheringNodes();
		// Fishing Spot IDs range from 1 to the mid 200s, add 10000 to fishing node IDs
		$this->fishingNodes(10000);
	}

	private function gatheringNodes()
	{
		$apiFields = [
			'ID',
			'GatheringType.ID',
			'GatheringLevel',
			'GameContentLinks.GatheringPoint.GatheringPointBase.0',
		];

		// Items go from Item0 to Item7
		$this->addNumberedFields($apiFields, 'Item%d', range(0, 7));

		// A note from Clorifex of Garland Tools
		//  You must be looking at gathering items.  What you're looking for there is the GatheringPoint table, which has a PlaceName (i.e., Cedarwood) and a TerritoryType.  The TerritoryType then has the PlaceName you're looking for - Lower La Noscea.
		//  Be warned that what I referred to as a 'node' is really a GatheringPointBase.  There are lots of gathering points with the same items because they appear in different places on the map.
		$this->loopEndpoint('gatheringpointbase', $apiFields, function($data) {
			if ($data->GameContentLinks->GatheringPoint->GatheringPointBase['0'] == null)
				return;

			// Loop through Item#
			$gatheringItemIds = [];
			foreach (range(0,7) as $i)
				if ($data->{'Item' . $i})
					$gatheringItemIds[] = $data->{'Item' . $i};

			if (empty($gatheringItemIds))
				return;

			sort($gatheringItemIds);

			$gi = $this->request('gatheringitem', [
				'ids' => implode(',', $gatheringItemIds),
				'columns' => [
					'Item',
				],
			])->Results;

			foreach ($gi as $gatheringItem)
				if ($gatheringItem)
					$this->setData('item_node', [
						'item_id' => $gatheringItem->Item,
						'node_id' => $data->ID,
					]);

			$gpColumns = ;

			$gp = $this->request('gatheringpoint/' . $data->GameContentLinks->GatheringPoint->GatheringPointBase['0'], ['columns' => [
				'PlaceName.ID',
				// 'TerritoryType.PlaceName.ID',
			]]);

			$this->setData('nodes', [
				'id'          => $data->ID,
				'type'        => $data->GatheringType->ID,
				'level'       => $data->GatheringLevel,
			], $data->ID);

			$this->setData('coordinates', [
				// If I got the zone_id wrong, try $gp->TerritoryType->PlaceName->ID instead
				'zone_id'         => $gp->PlaceName->ID,
				'coordinate_id'   => $data->ID,
				'coordinate_type' => 'node', // See Relation::morphMap in AppServiceProvider
				'x'               => null, // Filled in later
				'y'               => null, // Filled in later
				'z'               => null, // Filled in later
				'radius'          => null, // Filled in later
			]);

			$this->setData('details', [
				'detailable_id'   => $data->ID,
				'detailable_type' => 'node', // See Relation::morphMap in AppServiceProvider
				'data'            => [
					// Filled in later
					//  Timer data, etc
				]
			]);
		});
	}

	private function fishingNodes($idAdditive)
	{
		$apiFields = [
			'ID',
			'PlaceName.ID',
			'PlaceName.Name',
			'FishingSpotCategory',
			'GatheringLevel',
			'Radius',
			'X',
			'Z',
			// 'TerritoryType.PlaceName.ID',
		];

		// Items go from Item0 to Item9
		$this->addNumberedFields($apiFields, 'Item%dTargetID', range(0, 9));

		$this->loopEndpoint('fishingspot', $apiFields, function($data) use ($idAdditive) {
			// Skip empty names
			if ($data->PlaceName->Name == '')
				return;

			$nodeId = $data->ID + $idAdditive;

			// Loop through Item#
			$hasItems = false;
			foreach (range(0, 9) as $i)
				if ($data->{'Item' . $i . 'TargetID'})
				{
					$hasItems = true;
					$this->setData('item_node', [
						'item_id' => $data->{'Item' . $i . 'TargetID'},
						'node_id' => $data->ID,
					]);
				}

			// Don't include the fishing node if there aren't any items attached
			if ( ! $hasItems)
				return;

			$this->setData('nodes', [
				'id'          => $nodeId,
				// The variable comes back as "2", but it really correlates to "11"; 1 to 10, 3 to 12, etc
				'type'        => config('games.ffxiv.nodeTypes')[$var + 9] ?? null,
				'level'       => $data->GatheringLevel,
			], $nodeId);

			$this->setData('coordinates', [
				// If I got the zone_id wrong, try $data->TerritoryType->PlaceName->ID instead
				'zone_id'         => $data->PlaceName->ID,
				'coordinate_id'   => $nodeId,
				'coordinate_type' => 'node', // See Relation::morphMap in AppServiceProvider
				'x'               => 1 + ($data->X / 50), // Translate a number like 1203 to something like 25.06,
				'y'               => 1 + ($data->Z / 50),
				'z'               => null,
				'radius'          => $data->Radius,
			]);
		});
	}

	/**
	 * Helper Functions
	 */

	private function addLanguageFields(&$array, $sprintfableString) {
		foreach ($this->xivapiLanguages as $lang)
			array_push($array, sprintf($sprintfableString, $lang));
	}

	private function addNumberedFields(&$array, $sprintfableString, $numericalRange) {
		foreach ($numericalRange as $number)
			array_push($array, sprintf($sprintfableString, $number));
	}

	/**
	 * loopEndpoint - Loop around an XIVAPI Endpoint
	 * @param  string   $endpoint Any type of `/content`
	 * @param  array    $columns  Specific columns to reduce XIVAPI Load
	 * @param  function $callback $data is passed into here
	 * @param  array    $filters  An array of callback functions; A way to reduce identifiers even more
	 */
	private function loopEndpoint($endpoint, $columns, $callback, $filters = [])
	{
		$request = $this->listRequest($endpoint, ['columns' => ['ID']]);
		foreach ($request->chunk($this->chunkLimit !== null ? $this->chunkLimit : 100) as $chunk)
		{
			$ids = $chunk->map(function($item) {
				return $item->ID;
			});

			if (isset($filters['ids']))
				$ids = $ids->filter($filters['ids']);

			if (empty($ids))
				continue;

			$chunk = $this->request($endpoint, ['ids' => $ids->join(','), 'columns' => $columns]);

			foreach ($chunk->Results as $data)
				$callback($data);
		}
	}

	private function listRequest($content, $queries = [])
	{
		$queries['limit'] = $this->limit !== null ? $this->limit : 3000; // Maximum allowed per https://xivapi.com/docs/Game-Data#lists
		$queries['page'] = 1;

		$results = [];

		while (true)
		{
			// $response now contains ->Pagination and ->Results
			$response = $this->request($content, $queries);

			$results = array_merge($results, $response->Results);

			if ($response->Pagination->PageTotal == $response->Pagination->Page)
				break;

			$queries['page'] = $response->Pagination->PageNext;
		}

		return collect($results);
	}

	private function request($content, $queries = [])
	{
		return Cache::store('file')->rememberForever($content . serialize($queries), function() use ($content, $queries) {
			$this->command->info(
				'Querying: ' . $content .
				(isset($queries['ids']) ? ' ' . preg_replace('/,.+,/', '-', $queries['ids']) : '')
			);
			return $this->api->queries($queries)->content->{$content}()->list();
		});
	}

}
