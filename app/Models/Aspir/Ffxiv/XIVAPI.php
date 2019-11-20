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
		// Venture IDs range from 1 to ~900; ALLOTMENT 90,001 to 100k
		$this->ventures(90000);
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
				'data'            => json_encode([
					'xp'    => $data->ExpReward,
					'gil'   => $data->GilReward,
					'plate' => $data->LeveVfx->IconID,
					'frame' => $data->LeveVfxFrame->IconID,
				]),
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
				if (isset($data->LeveClient->{'Name_' . $lang}) && $data->LeveClient->{'Name_' . $lang})
					$this->setData('npc_translations', [
						'npc_id' => $targetId,
						'locale' => $lang,
						'name'   => $data->LeveClient->{'Name_' . $lang},
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
						'quantity'     => $data->{'ItemCountReward0' . $slot} ?? 1,
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
						'quantity'     => $data->{'ItemCountReward1' . $slot} ?? 1,
						'quality'      => null,
						'rate'         => null,
					]);
		});

		$this->limit = null;
	}

	protected function ventures($idAdditive)
	{
		$ventureTypeId = array_search('venture', config('games.ffxiv.objectiveTypes'));

		if ($ventureTypeId === false)
			$this->error('Could not find Objective Type ID for "venture"');

		$apiFields = [
			'ID',
			'ClassJobCategory.ID',
			'RetainerLevel',
			'MaxTimeMin',
			'VentureCost',
			'IsRandom',
			'Task',
		];

		$this->addLanguageFields($apiFields, 'ClassJobCategory.Name_%s');

		$nameFields = [];
		$this->addLanguageFields($nameFields, 'Name_%s');

		$this->loopEndpoint('retainertask', $apiFields, function($data) use ($idAdditive, $ventureTypeId, $nameFields) {
			// The Quantities are only applicable for "Normal" Ventures
			$objectiveId = $data->ID + $idAdditive;
			$names = $quantities = [];

			if ($data->IsRandom)
				$names = (array) $this->request('retainertaskrandom/' . $data->Task, ['columns' => $nameFields]);
			else
			{
				/**
				 * There's something I'm not grasping about Ventures.
				 *  I think my item list is going to be too loose here, but the point gets across.
				 *  Cannot determine difference between "Mining/Botany/Fishing/Hunting/Quick" Explorations.
				 *  Giving it a dummy name.
				 */
				$names['Name_en'] = 'Venture Exploration';

				$q = $this->request('retainertasknormal/' . $data->Task, ['columns' => [
					'Quantity0',
					'Quantity1',
					'Quantity2',
					'ItemTarget',
					'ItemTargetID',
				]]);

				if ($q->ItemTarget == 'Item' && $q->ItemTargetID)
					foreach (range(0, 2) as $slot)
						$this->setData('item_objective', [
							'item_id'      => $q->ItemTargetID,
							'objective_id' => $objectiveId,
							'reward'       => 1,
							'quantity'     => $q->{'Quantity' . $slot},
							'quality'      => $data->{'ItemCountReward1' . $slot} ?? 1,
							'rate'         => 33, // Reward is time based, simplifying and saying 33% chance for 3 tiers
						]);
			}

			$this->setData('objectives', [
				'id'         => $objectiveId,
				'niche_id'   => $data->ClassJobCategory->ID,
				'issuer_id'  => null,
				'target_id'  => null,
				'type'       => $ventureTypeId,
				'repeatable' => 1,
				'level'      => $data->RetainerLevel,
				'icon'       => null,
			], $objectiveId);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('objective_translations', [
					'objective_id' => $objectiveId,
					'locale'       => $lang,
					'name'         => $names->{'Name_' . $lang} ?? $names['Name_en'],
					'description'  => null,
				]);

			$this->setData('details', [
				'detailable_id'   => $objectiveId,
				'detailable_type' => 'objective', // See Relation::morphMap in AppServiceProvider
				'data'            => json_encode([
					'cost'    => $data->VentureCost,
					'minutes' => $data->MaxTimeMin,
				]),
			]);
		});
	}

	public function merchants()
	{
		// Gilshop IDs range from 262k+, and there's less than 1000
		$this->gilshops();
		// Special Shop IDs range from 1.7mil+, and there's less than 1000
		$this->specialshops();
	}

	private function gilshops()
	{
		$apiFields = [
			'ID',
			'Items.*.ID',
			'Items.*.PriceLow',
			'Items.*.PriceMid',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');

		$this->loopEndpoint('gilshop', $apiFields, function($data) {
			$this->setData('shops', [
				'id' => $data->ID,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				if (isset($data->{'Name_' . $lang}) && $data->{'Name_' . $lang})
					$this->setData('shop_translations', [
						'shop_id' => $data->ID,
						'locale'  => $lang,
						'name'    => $data->{'Name_' . $lang},
					]);

			foreach ($data->Items as $item)
			{
				$this->setData('item_shop', [
					'item_id' => $item->ID,
					'shop_id' => $data->ID,
				]);

				if ($item->PriceLow)
					$this->setData('item_price', [
						'item_id'  => $item->ID,
						'price_id' => $this->generatePriceId([
							'quality' => 0,
							'item_id' => null, // The alternative currency; null is Gil
							'market'  => 0, // PriceLow is selling price
							'amount'  => $item->PriceLow,
						]),
					]);

				if ($item->PriceMid)
					$this->setData('item_price', [
						'item_id'  => $item->ID,
						'price_id' => $this->generatePriceId([
							'quality' => 0,
							'item_id' => null, // The alternative currency; null is Gil
							'market'  => 1, // PriceMid is buying price
							'amount'  => $item->PriceMid,
						]),
					]);
			}
		});
	}

	private function specialshops()
	{
		// Calling individually is the only way to easily get every field
		$this->chunkLimit = 1;
		$apiFields = null;

		$this->loopEndpoint('specialshop', $apiFields, function($data) {
			$this->setData('shops', [
				'id' => $data->ID,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				if (isset($data->{'Name_' . $lang}) && $data->{'Name_' . $lang})
					$this->setData('shop_translations', [
						'shop_id' => $data->ID,
						'locale'  => $lang,
						'name'    => $data->{'Name_' . $lang},
					]);

			foreach (range(0, 59) as $first)
				foreach (range(0, 2) as $second)
				{
					// 00, 01, 02,  10, 11, 12,  ...,  590, 591, 592
					$number = implode('', [$first, $second]);

					$itemId = $data->{'ItemReceive' . $number . 'TargetID'} ?? null;
					$currencyId = $data->{'ItemCost' . $number . 'TargetID'} ?? null;
					$nqAmount = $data->{'CountCost' . $number} ?? null;
					$hqAmount = $data->{'HQCost' . $number} ?? null;

					if ( ! ($itemId && $currencyId))
						continue;

					$this->setData('item_shop', [
						'item_id' => $itemId,
						'shop_id' => $data->ID,
					]);

					// You can only buy things from a special shop
					//  Gil shops handle the selling

					if ($nqAmount)
						$this->setData('item_price', [
							'shop_id'  => $data->ID,
							'price_id' => $this->generatePriceId([
								'quality' => 0,
								'item_id' => $currencyId, // The alternative currency; null is Gil
								'market'  => 1, // PriceLow is selling price
								'amount'  => $nqAmount,
							]),
						]);

					if ($hqAmount)
						$this->setData('item_price', [
							'shop_id'  => $data->ID,
							'price_id' => $this->generatePriceId([
								'quality' => 1,
								'item_id' => $currencyId, // The alternative currency; null is Gil
								'market'  => 1, // PriceLow is selling price
								'amount'  => $hqAmount,
							]),
						]);
				}
		});

		$this->chunkLimit = null;
	}

	public function zones()
	{
		// placename IDs range from 1 to 3300+
		$this->placenames();
		// instances are already in placename, but additional data is available
		$this->instances();
	}

	private function placenames()
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

	private function instances()
	{
		$apiFields = [
			'ID',
			'ContentType.ID',
			'ContentFinderCondition.TerritoryType.PlaceName.ID',
			'ContentFinderCondition.ImageID',
		];

		$this->loopEndpoint('instancecontent', $apiFields, function($data) {
			$zoneId = $data->ContentFinderCondition->TerritoryType->PlaceName->ID ?? null;

			if ($zoneId === null)
				return;

			$this->setData('details', [
				'detailable_id'   => $zoneId,
				'detailable_type' => 'zone', // See Relation::morphMap in AppServiceProvider
				'data'            => json_encode([
					'type' => $data->ContentType->ID,
					'icon' => $data->ContentFinderCondition->ImageID,
				]),
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

			$gp = $this->request('gatheringpoint/' . $data->GameContentLinks->GatheringPoint->GatheringPointBase['0'], ['columns' => [
				'PlaceName.ID',
				'TerritoryType.PlaceName.ID',
			]]);

			$this->setData('nodes', [
				'id'          => $data->ID,
				'type'        => $data->GatheringType->ID,
				'level'       => $data->GatheringLevel,
			], $data->ID);

			$this->setData('coordinates', [
				'zone_id'         => $gp->TerritoryType->PlaceName->ID,
				'coordinate_id'   => $data->ID,
				'coordinate_type' => 'node', // See Relation::morphMap in AppServiceProvider
				'x'               => null, // Filled in later
				'y'               => null, // Filled in later
				'z'               => null, // Filled in later
				'radius'          => null, // Filled in later
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
				'type'        => config('games.ffxiv.nodeTypes')[$data->FishingSpotCategory + 9] ?? null,
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

	public function npcs()
	{
		// enpcresident IDs start at 1000000
		$this->residents();
		// bncpname IDs range from 1 to 9000+
		$this->mobs();
	}

	private function residents()
	{
		// 3000 calls were taking over the allotted 10s call limit imposed by XIVAPI's Guzzle Implementation
		$this->limit = 500;

		$apiFields = [
			'ID',
			'GilShop.*.ID',
			'SpecialShop.*.ID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'GilShop.*.Name_%s');
		$this->addLanguageFields($apiFields, 'SpecialShop.*.Name_%s');

		$this->loopEndpoint('enpcresident', $apiFields, function($data) {
			// Skip empty names
			if ($data->Name_en == '')
				return;

			$this->setData('npcs', [
				'id'    => $data->ID,
				'enemy' => 0,
				'level' => null,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				if (isset($data->{'Name_' . $lang}) && $data->{'Name_' . $lang})
					$this->setData('npc_translations', [
						'npc_id' => $data->ID,
						'locale' => $lang,
						'name'   => $data->{'Name_' . $lang},
					]);

			foreach (['GilShop', 'SpecialShop'] as $shopType)
				if ($data->$shopType)
					foreach ($data->$shopType as $shop)
						if ($shop->ID)
							$this->setData('npc_shop', [
								'shop_id' => $shop->ID,
								'npc_id'  => $data->ID,
							]);
		});

		$this->limit = null;
	}

	private function mobs()
	{
		$apiFields = [
			'ID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');

		$this->loopEndpoint('bnpcname', $apiFields, function($data) {
			// Skip empty names
			if ($data->Name_en == '')
				return;

			$this->setData('npcs', [
				'id'    => $data->ID,
				'enemy' => 1,
				'level' => null, // Filled in later
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				if (isset($data->{'Name_' . $lang}) && $data->{'Name_' . $lang})
					$this->setData('npc_translations', [
						'npc_id' => $data->ID,
						'locale' => $lang,
						'name'   => $data->{'Name_' . $lang},
					]);
		});
	}

	public function jobs()
	{
		$apiFields = [
			'ID',
			'ClassJobCategory.Name',
			'StartingLevel',
			'ItemSoulCrystalTargetID',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'Abbreviation_%s');

		$this->loopEndpoint('classjob', $apiFields, function($data) {
			// 'battle', 'crafting', 'gathering'
			$type = 'battle';
			if (strpos($data->ClassJobCategory->Name, 'Hand') !== false)
				$type = 'crafting';
			elseif (strpos($data->ClassJobCategory->Name, 'Land') !== false)
				$type = 'gathering';

			// 0 = Classes, 1 = Jobs, 2 = Adv. Jobs
			$tier = 0;
			if ($data->StartingLevel > 1)
				$tier = 2;
			if ($type == 'battle' && $data->ItemSoulCrystalTargetID)
				$tier = 1;

			$this->setData('jobs', [
				'id'   => $data->ID,
				'type' => $type,
				'tier' => $tier,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('job_translations', [
					'job_id'       => $data->ID,
					'locale'       => $lang,
					'name'         => $data->{'Name_' . $lang} ?? null,
					'abbreviation' => $data->{'Abbreviation_' . $lang} ?? null,
				]);
		});
	}

	public function jobCategories()
	{
		// classjobcategory has a datapoint for every job abbreviation;
		//  use the translations we collected previously as a shortcut
		$abbreviations = collect($this->data['job_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('job_id')->map(function($entry) {
			return $entry['abbreviation'];
		});

		// In addition to ID, also get ACN, ALC, NIN, SAM, etc
		$apiFields = array_merge([
			'ID',
		], $abbreviations->toArray());

		$this->loopEndpoint('classjobcategory', $apiFields, function($data) use ($abbreviations) {
			$this->setData('niches', [
				'id'   => $data->ID,
			], $data->ID);

			foreach ($abbreviations as $jobId => $abbreviation)
				if ($data->$abbreviation == 1)
					$this->setData('job_niche', [
						'job_id'   => $jobId,
						'niche_id' => $data->ID,
					]);
		});
	}

	public function itemCategories()
	{
		$apiFields = [
			'ID',
			'OrderMajor',
			'OrderMinor',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');

		$this->loopEndpoint('itemuicategory', $apiFields, function($data) {
			$this->setData('categories', [
				'id'          => $data->ID,
				'category_id' => null, // Parent Category ID
				'rank'        => ($data->OrderMajor * 1000) + $data->OrderMinor,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('category_translations', [
					'category_id' => $data->ID,
					'locale'      => $lang,
					'name'        => $data->{'Name_' . $lang} ?? null,
				]);
		});
	}

	public function attributes()
	{
		$apiFields = [
			'ID',
			'Order',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'Description_%s');

		$this->loopEndpoint('baseparam', $apiFields, function($data) {
			$this->setData('attributes', [
				'id'   => $data->ID,
				'rank' => $data->Order >= 0 ? $data->Order : null,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('attribute_translations', [
					'attribute_id' => $data->ID,
					'locale'       => $lang,
					'name'         => $data->{'Name_' . $lang} ?? null,
					'description'  => $data->{'Description_' . $lang} ?? null,
				]);
		});
	}

	public function items()
	{
		// 3000 calls were taking over the allotted 10s call limit imposed by XIVAPI's Guzzle Implementation
		$this->limit = 1000;

		$attributes = collect($this->data['attribute_translations'])->filter(function($entry) {
			return $entry['locale'] == 'en';
		})->keyBy('attribute_id')->map(function($entry) {
			return $entry['name'];
		})->toArray();

		$delayAttributeId = array_search('Delay', $attributes);
		$hpAttributeId = array_search('HP', $attributes);
		$mpAttributeId = array_search('MP', $attributes);
		$tpAttributeId = array_search('TP', $attributes);

		$apiFields = [
			'ID',
			'PriceLow',
			'PriceMid',
			'LevelEquip',
			'LevelItem',
			'ItemUICategory.ID',
			'IsEquippable',
			'IsUnique',
			'ClassJobCategoryTargetID',
			'IsUntradable',
			'EquipSlotCategory.ID',
			'Rarity',
			'IconID',
			'MateriaSlotCount',
			// Special X != Normal X
			'CanBeHq', // AKA Special
			// Materia Values might always be 0, TODO double check and Manual if needed
			'Materia.BaseParam.ID',
			'Materia.Value',
			// ItemAction contains a myriad of things
			// https://github.com/viion/ffxiv-datamining/blob/master/docs/ItemActions.md
			//  max attribute values
			//  potion values
			//  item food connections
			'ItemAction',
		];

		$this->addLanguageFields($apiFields, 'Name_%s');
		$this->addLanguageFields($apiFields, 'Description_%s');
		// Attribute Hunting
		$this->addNumberedFields($apiFields, 'BaseParam%dTargetID', range(0, 5));
		$this->addNumberedFields($apiFields, 'BaseParamSpecial%dTargetID', range(0, 5));
		$this->addNumberedFields($apiFields, 'BaseParamValue%d', range(0, 5));
		$this->addNumberedFields($apiFields, 'BaseParamValueSpecial%d', range(0, 5));

		// Base Attributes
		$rootParamConversion = [
			'Block'       => array_search('Block Strength', $attributes),
			'BlockRate'   => array_search('Block Rate', $attributes),
			'DefenseMag'  => array_search('Magic Defense', $attributes),
			'DefensePhys' => array_search('Defense', $attributes),
			'DamageMag'   => array_search('Magic Damage', $attributes),
			'DamagePhys'  => array_search('Physical Damage', $attributes),
			'DelayMs'     => array_search('Delay', $attributes),
		];

		// HQs of these exist as Special, will need to match on names
		$apiFields = array_merge($apiFields, array_keys($rootParamConversion));

		$this->loopEndpoint('item', $apiFields, function($data) use ($rootParamConversion, $delayAttributeId, $hpAttributeId, $mpAttributeId, $tpAttributeId) {
			// Empty name, or "dated" item, skip
			if ($data->Name_en == '' || substr($data->Name_en, 0, 6) == 'Dated ')
				return;

			$this->setData('items', [
				'id'          => $data->ID,
				'ilvl'        => $data->LevelItem,
				'category_id' => $data->ItemUICategory->ID,
				'rarity'      => $data->Rarity,
				'icon'        => $data->IconID,
			], $data->ID);

			foreach ($this->xivapiLanguages as $lang)
				$this->setData('item_translations', [
					'item_id'     => $data->ID,
					'locale'      => $lang,
					'name'        => $data->{'Name_' . $lang} ?? null,
					'description' => $data->{'Description_' . $lang} ?? null,
				]);

			if ($data->IsEquippable)
				$this->setData('equipment', [
					'id'       => $data->ID, // Each item can only have one equipment option, so sharing IDs is safe
					'item_id'  => $data->ID,
					'niche_id' => $data->ClassJobCategoryTargetID,
					'slot'     => $data->EquipSlotCategory->ID,
					'level'    => $data->LevelEquip,
					'sockets'  => $data->MateriaSlotCount,
				]);

			$this->setData('details', [
				'detailable_id'   => $data->ID,
				'detailable_type' => 'item', // See Relation::morphMap in AppServiceProvider
				'data'            => json_encode([
					'unique'    => $data->IsUnique,
					'tradeable' => $data->IsUntradable ? null : 1,
				]),
			]);

			// Attribute Data
			$nqParams = $hqParams = $maxParams = [];

			foreach ($rootParamConversion as $key => $rootParamAttributeId)
				if ($data->$key)
					$nqParams[$rootParamAttributeId] = $data->$key;

			// Delay comes through as "2000", but we want it as "2.00"
			if (isset($nqParams[$delayAttributeId]))
				$nqParams[$delayAttributeId] /= 1000;

			if ($data->Materia->BaseParam->ID && $data->Materia->Value)
				$nqParams[$data->Materia->BaseParam->ID] = $data->Materia->Value;

			foreach (range(0, 5) as $slot)
				if ($data->{'BaseParam' . $slot . 'TargetID'} > 0)
					$nqParams[$data->{'BaseParam' . $slot . 'TargetID'}] = $data->{'BaseParamValue' . $slot};

			if ($data->CanBeHq)
				foreach (range(0, 5) as $slot)
					if ($data->{'BaseParamSpecial' . $slot . 'TargetID'} > 0 && $data->{'BaseParam' . $slot . 'TargetID'} > 0)
						$hqParams[$data->{'BaseParamSpecial' . $slot . 'TargetID'}] = $data->{'BaseParamValue' . $slot} + $data->{'BaseParamValueSpecial' . $slot};

			// Item Actions provide Attribute Data
			//  Not using $nqParams or $hqParams here
			if ($data->ItemAction)
			{
				$dataQualitySlots = [ '' => 'nq' ];
				if ($data->CanBeHq)
					$dataQualitySlots['HQ'] = 'hq';

				switch ($data->ItemAction->Type)
				{
					// Health potions, eg: X-Potion
					case 847:
						foreach ($dataQualitySlots as $qualitySlot => $quality)
							${$quality . 'Params'}[$hpAttributeId] = $data->ItemAction->{'Data' . $qualitySlot . '1'}; // data_1 = max
						break;
					// Ether MP potions, eg: X-Ether
					case 848:
						foreach ($dataQualitySlots as $qualitySlot => $quality)
							${$quality . 'Params'}[$mpAttributeId] = $data->ItemAction->{'Data' . $qualitySlot . '1'}; // data_1 = max
						break;
					// Elixir potions, restores both HP and MP
					case 849:
						foreach ($dataQualitySlots as $qualitySlot => $quality)
							${$quality . 'Params'}[$hpAttributeId] = $data->ItemAction->{'Data' . $qualitySlot . '1'}; // data_1 = max hp
						foreach ($dataQualitySlots as $qualitySlot => $quality)
							${$quality . 'Params'}[$mpAttributeId] = $data->ItemAction->{'Data' . $qualitySlot . '3'}; // data_3 = max mp
						break;
					// Wings, eg: Icarus Wing, restores TP
					case 1767:
						foreach ($dataQualitySlots as $qualitySlot => $quality)
							${$quality . 'Params'}[$tpAttributeId] = $data->ItemAction->{'Data' . $qualitySlot . '3'}; // data_3 = max tp
						break;
					// Crafting + Gathering Food
					// Battle Food
					// Attribute Potions, eg: X-Potion of Dexterity
					// data_1 = `ItemFood`
					// data_2 = Duration in seconds
					case 844:
					case 845:
					case 846:
						$foodColumns = [];
						$this->addNumberedFields($foodColumns, 'BaseParam%d.ID', range(0, 2));
						$this->addNumberedFields($foodColumns, 'Value%d', range(0, 2));
						$this->addNumberedFields($foodColumns, 'ValueHQ%d', range(0, 2));
						$this->addNumberedFields($foodColumns, 'IsRelative%d', range(0, 2));
						$this->addNumberedFields($foodColumns, 'Max%d', range(0, 2));
						$this->addNumberedFields($foodColumns, 'MaxHQ%d', range(0, 2));

						$food = $this->request('itemfood/' . $data->ItemAction->Data1, ['columns' => $foodColumns]);

						foreach (range(0, 2) as $slot)
							if ($food->{'BaseParam' . $slot}->ID)
								foreach ($dataQualitySlots as $qualitySlot => $quality)
									${$quality . 'Params'}[$food->{'BaseParam' . $slot}->ID] = $food->{'IsRelative' . $slot}
										? $food->{'Max' . strtoupper($qualitySlot) . $slot}
										: $food->{'Value' . strtoupper($qualitySlot) . $slot};
						break;
				}
			}

			foreach ([0 => 'nq', 1 => 'hq'] as $qualityValue => $quality)
				foreach (${$quality . 'Params'} as $attributeId => $amount)
					if ($attributeId && $amount)
						$this->setData('attribute_item', [
							'item_id'      => $data->ID,
							'attribute_id' => $attributeId,
							'quality'      => $qualityValue,
							'value'        => $amount,
						]);
		});

		$this->limit = null;
	}

	public function recipes()
	{
		// Recipe IDs range 1 to < 10000
		$this->craftingRecipes();
		// Company Craft IDs range from 1+; start them at 100k
		$this->companyCrafts(100000);
	}

	private function craftingRecipes()
	{
		// 3000 calls were taking over the allotted 10s call limit imposed by XIVAPI's Guzzle Implementation
		$this->limit = 500;

		$apiFields = [
			'ID',
			'ClassJob.ID',
			'ItemResultTargetID',
			'RecipeLevelTable.ClassJobLevel',
			'RecipeLevelTable.Stars',
			'AmountResult',
			'CanHq',
		];

		// There are 10 possible ingredient items (reagents)
		$this->addNumberedFields($apiFields, 'ItemIngredient%dTargetID', range(0, 9));
		$this->addNumberedFields($apiFields, 'AmountIngredient%d', range(0, 9));

		$this->loopEndpoint('recipe', $apiFields, function($data) {
			if ( ! $data->ItemResultTargetID)
				return;

			$this->setData('recipes', [
				'id'           => $data->ID,
				'item_id'      => $data->ItemResultTargetID,
				'job_id'       => $data->ClassJob->ID,
				'level'        => $data->RecipeLevelTable->ClassJobLevel,
				'sublevel'     => $data->RecipeLevelTable->Stars,
				'yield'        => $data->AmountResult,
				'quality'      => $data->CanHq ? 1 : null,
				'chance'       => null,
			], $data->ID);

			foreach (range(0, 9) as $slot)
				if ($data->{'ItemIngredient' . $slot . 'TargetID'} && $data->{'AmountIngredient' . $slot})
					$this->setData('item_recipe', [
						'item_id'   => $data->{'ItemIngredient' . $slot . 'TargetID'},
						'recipe_id' => $data->ID,
						'quantity'  => $data->{'AmountIngredient' . $slot},
					]);
		});

		$this->limit = null;
	}

	private function companyCrafts($idAdditive)
	{
		$apiFields = [
			'ID',
			'ResultItemTargetID',
		];

		// There are 8 possible crafting parts (reagents)
		$this->addNumberedFields($apiFields, 'CompanyCraftPart%d', range(0, 7));

		$this->loopEndpoint('companycraftsequence', $apiFields, function($data) use ($idAdditive) {

			$recipeId = $data->ID + $idAdditive;

			$this->setData('recipes', [
				'id'           => $recipeId,
				'item_id'      => $data->ResultItemTargetID,
				'job_id'       => 0,
				'level'        => 1,
				'sublevel'     => 0,
				'yield'        => 1,
				'quality'      => null,
				'chance'       => null,
			], $recipeId);

			foreach (range(0, 7) as $partSlot)
				if ($data->{'CompanyCraftPart' . $partSlot})
					foreach (range(0, 2) as $processSlot)
					{
						$process =& $data->{'CompanyCraftPart' . $partSlot}->{'CompanyCraftProcess' . $processSlot};
						if ($process)
							foreach (range(0, 11) as $setSlot)
								if ($process->{'SetQuantity' . $setSlot})
									$this->setData('item_recipe', [
										'item_id'   => $process->{'SupplyItem' . $setSlot}->Item,
										'recipe_id' => $recipeId,
										'quantity'  => $process->{'SetQuantity' . $setSlot} * $process->{'SetsRequired' . $setSlot},
									]);
					}
		});
	}

	/**
	 * Helper Functions
	 */

	private function addLanguageFields(&$array, $sprintfableString)
	{
		foreach ($this->xivapiLanguages as $lang)
			array_push($array, sprintf($sprintfableString, $lang));
	}

	private function addNumberedFields(&$array, $sprintfableString, $numericalRange)
	{
		foreach ($numericalRange as $number)
			array_push($array, sprintf($sprintfableString, $number));
	}

	private function generatePriceId($priceData)
	{
		$pricingId = array_search($priceData, $this->data['prices']);

		if ($pricingId !== false)
			return $pricingId;

		return $this->setData('prices', $priceData);
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

			if ($ids->isEmpty())
				continue;
			elseif ($ids->count() > 1)
			{
				$chunk = $this->request($endpoint, ['ids' => $ids->join(','), 'columns' => $columns]);

				foreach ($chunk->Results as $data)
					$callback($data);
			}
			else
				$callback($this->requestOne($endpoint, $ids->first()));
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

	private function requestOne($content, $id)
	{
		return Cache::store('file')->rememberForever($content . $id, function() use ($content, $id) {
			$this->command->info('Querying ' . $content . ' (' . $id . ')');
			return $this->api->content->{$content}()->one($id);
		});
	}

}
