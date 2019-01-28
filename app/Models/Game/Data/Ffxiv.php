<?php

/**
 * Data provided manually by /u/Clorifex of garlandtools.org, thanks dude!
 *
 * php artisan osmose:parse ffxiv
 */

namespace App\Models\Game\Data;

use App\Models\Game\Data\GameDataTemplate;

class Ffxiv extends GameDataTemplate
{

	public
		$core = null,
		$languages = [
			'en' => '/en',
			'de' => '/de',
			'fr' => '/fr',
			'ja' => '/ja',
		],
		$runList = [
			'core',
			'jobs',
			'niches',
			'categories',
			'attributes',
			'zones',
			'npcs',
			'objectives',
			'nodes',
			'items',
		];

	/**
	 * Table Functions
	 */

	protected function core()
	{
		$core = $this->getJSON($this->originDataLocation . 'core.js', true);
		$this->saveMap('core', $core);
	}

	protected function attributes()
	{
		// Attribute Ids are non-existent
		$attributesMap = $this->map['attributes'] ?? [];

		// Set up the columns as the first row of data
		$attributesData = [
			[ 'id', ],
		];
		$attributeTranslationsData = [
			[ 'attribute_id', 'locale', 'name', ],
		];
		// "Fixed" Data - Attribute names had ucwords() ran against them

		$newAttributeId = empty($attributesMap) ? 1 : (max($attributesMap) + 1);

		// Only English translations are available
		$itemsDir = $this->originDataLocation . 'data/en/item';

		// Only find item files with attributes
		$filesList = $this->grepDir($itemsDir, '"attr');

		$lastKey = count($filesList) - 1; // $filesList had array_values ran against it, count()ing for $lastKey is a safe bet

		foreach ($filesList as $key => $fullFilePath)
		{
			$data = $this->getJSON($fullFilePath)['item'];

			if (isset($data['attr']))
			{
				// Normalize food/potion buffs
				// Looking for attr and attr_hq
				foreach (['', '_hq'] as $quality)
				{
					if ( ! isset($data['attr' . $quality]))
						continue;

					// attr or attr_hq
					$attributes = $data['attr' . $quality];

					if (isset($attributes['action']))
					{
						$normalizedAttributes = [];
						foreach ($attributes['action'] as $attribute => $definition)
							$normalizedAttributes[$attribute] = $definition['rate'];
						$attributes = $normalizedAttributes;
					}

					foreach (array_keys($attributes) as $attributeName)
					{
						$attributeName = ucwords($attributeName);

						// Attributes don't have an ID we can already use
						// Save the attribute down for later referential usage
						$attributeId = $attributesMap[$attributeName] ?? $newAttributeId++;
						$attributesMap[$attributeName] = $attributeId;

						$attributesData[] = [
							/* id */	$attributeId,
						];

						// Only EN translation available
						$attributeTranslationsData[] = [
							/* attribute_id */	$attributeId,
							/* locale */		'en',
							/* name */			$this->clean($attributeName),
						];
					}
				}
			}

			// Only update progress every 5 steps
			if ($key % 5 == 0)
				$this->progress($key, $lastKey);
		}

		$this->progress($key, $lastKey, true);

		$this->write('attributes', $attributesData);
		$this->write('attribute_translations', $attributeTranslationsData);

		$this->saveMap('attributes', $attributesMap);
	}

	protected function categories()
	{
		// $categoriesMap unnecessary, reliable id
		// Set up the columns as the first row of data
		$categoriesData = [
			[ 'id', 'category_id', ],
		];
		$categoryTranslationsData = [
			[ 'category_id', 'locale', 'name', ],
		];
		// "Fixed" Data - Any ID less than one was ignored

		$parentCategories = $this->getJSON($this->originDataLocation . 'custom/categoryParents.json');
		// Parent categories is a custom/manually maintained file
		foreach ($parentCategories as $data)
		{
			$categoriesData[] = [
				/* id */			$data['id'],
				/* category_id */	null,
			];
			// Only EN translation available
			$categoryTranslationsData[] = [
				/* category_id */	$data['id'],
				/* locale */		'en',
				/* name */			$this->clean($data['name']),
			];
		}

		$categories = array_values($this->map['core']['item']['categoryIndex']);
		foreach ($categories as $key => $data)
		{
			if ($data['id'] < 1)
				continue;

			// Find the ParentId
			$parentId = null;
			foreach ($parentCategories as $pc)
				if (isset($pc['catchall']) || in_array($data['id'], $pc['children']))
				{
					$parentId = $pc['id'];
					break;
				}

			$categoriesData[] = [
				/* id */			$data['id'],
				/* category_id */	$parentId,
			];
			// Only EN translation available
			$categoryTranslationsData[] = [
				/* category_id */	$data['id'],
				/* locale */		'en',
				/* name */			$this->clean($data['name']),
			];
		}

		$this->write('categories', $categoriesData);
		$this->write('category_translations', $categoryTranslationsData);
	}

	protected function jobs()
	{
		// Set up the columns as the first row of data
		$jobsData = [
			[ 'id', 'type', 'tier', ],
		];
		$jobTranslationsData = [
			[ 'job_id', 'locale', 'name', 'abbreviation', ],
		];

		$jobs = array_values($this->map['core']['jobs']);

		foreach ($jobs as $key => $data)
		{
			$type =  strstr($data['category'], 'Hand') ? 'crafting'  :
					(strstr($data['category'], 'Land') ? 'gathering' :
														 'battle'    );
			$tier = isset($data['isJob']) ? ($data['startingLevel'] > 1 ? 2 : 1) : 0;

			// Adventurer is listed as Disciple of War, but we're treating it as a crafting type
			if ($data['id'] == 0) {
				$type = 'crafting';
				$tier = 1;
			}

			// Fix the ID for Adventurer
			$data['id'] = $this->fixJobId($data['id']);

			$jobsData[] = [
				/* id */	$data['id'],
				/* type */	$type,
				/* tier */	$tier,
			];

			// Only EN translation available
			$jobTranslationsData[] = [
				/* job_id */		$data['id'],
				/* locale */		'en',
				/* name */			$this->clean($data['name']),
				/* abbreviation */	$this->clean($data['abbreviation']),
			];
		}

		$this->write('jobs', $jobsData);
		$this->write('job_translations', $jobTranslationsData);
	}

	protected function niches()
	{
		// Set up the columns as the first row of data
		$nicheData = [
			[ 'id', ],
		];
		$jobNicheData = [
			[ 'job_id', 'niche_id', ],
		];

		foreach ($this->map['core']['jobCategories'] as $data)
		{
			$nicheData[] =[
				/* niche_id */	$data['id'],
			];

			foreach ($data['jobs'] as $jobId)
				$jobNicheData[] = [
					/* job_id */	$this->fixJobId($jobId),
					/* niche_id */	$data['id'],
				];
		}

		$this->write('niches', $nicheData);
		$this->write('job_niche', $jobNicheData);
	}

	protected function zones()
	{
		// $zonesMap unnecessary, reliable id
		// Set up the columns as the first row of data
		$zonesData = [
			[ 'id', 'zone_id', ],
		];
		$zoneTranslationsData = [
			[ 'zone_id', 'locale', 'name', ],
		];
		// "Fixed" Data - Instance IDs are altered

		$zones = array_values($this->map['core']['locationIndex']);

		foreach ($zones as $data)
		{
			$zonesData[] = [
				/* id */		$data['id'],
				/* zone_id */	$data['parentId'] ?? null,
			];

			// Only EN translation available
			$zoneTranslationsData[] = [
				/* zone_id */		$data['id'],
				/* locale */		'en',
				/* name */			$this->clean($data['name']),
			];
		}

		// Also handle instances, which are basically zones
		echo 'Starting instances' . PHP_EOL;

		$instancesDir = $this->originDataLocation . 'data/en/instance';
		$filesList = $this->scanDir($instancesDir);
		$lastKey = count($filesList) - 1; // $filesList had array_values ran against it, count()ing for $lastKey is a safe bet

		foreach ($filesList as $key => $file)
		{
			$data = $this->getJSON($instancesDir . '/' . $file)['instance'];

			$data['id'] = $this->fixInstanceId($data['id']);

			// Sometimes `en` doesn't exist, but `name` does
			if ( ! isset($data['en']) && isset($data['name']))
				$data['en']['name'] = $data['name'];

			$zonesData[] = [
				/* id */		$data['id'],
				/* zone_id */	$data['zoneid'] ?? null,
			];

			// Only EN translation available
			foreach (config('translatable.locales') as $locale)
				if (isset($data[$locale]))
					$zoneTranslationsData[] = [
						/* zone_id */		$data['id'],
						/* locale */		$locale,
						// Super rare that name isn't set for JA.  Just use EN name instead
						/* name */			$this->clean($data[$locale]['name'] ?? $data['en']['name']),
					];

			// Only update progress every 5 steps
			if ($key % 5 == 0)
				$this->progress($key, $lastKey);
		}
		$this->progress($key, $lastKey, true);

		$this->write('zones', $zonesData);
		$this->write('zone_translations', $zoneTranslationsData);
	}

	protected function npcs()
	{
		// NPC Ids are unreliable, convert them
		$npcsMap = $this->map['npcs'] ?? [];
		$shopsMap = $this->map['shops'] ?? [];

		// Set up the columns as the first row of data
		$npcsData = [
			[ 'id', 'enemy', 'level', ],
		];
		$npcTranslationsData = [
			[ 'npc_id', 'locale', 'name', ],
		];
		$npcCoordinatesData = [
			[ 'zone_id', 'coordinate_id', 'coordinate_type', 'x', 'y', 'z', 'radius', ],
		];
		$npcDetailsData = [
			[ 'detailable_id', 'detailable_type', 'data', ],
		];
		$npcShopData = [
			[ 'npc_id', 'shop_id', ],
		];
		$shopsData = [
			[ 'id', ],
		];
		$shopTranslationsData = [
			[ 'shop_id', 'locale', 'name', ],
		];
		$shopCoordinatesData = [
			[ 'zone_id', 'coordinate_id', 'coordinate_type', 'x', 'y', 'z', 'radius', ],
		];

		// The type of npc features I want to save down
		$npcFeatures = [ 'race', 'tribe', 'gender', 'skinColorCode', 'hairColorCode', ];
		$mobFeatures = [ 'lvl', ];

		$newNpcId = empty($npcsMap) ? 1 : (max($npcsMap) + 1);
		$newShopId = empty($shopsMap) ? 1 : (max($shopsMap) + 1);

		// Also handle mobs, which are basically npcs
		foreach (['npc', 'mob'] as $npcType)
		{
			if ($npcType == 'mob')
				echo 'Starting mobs' . PHP_EOL;

			$npcsDir = $this->originDataLocation . 'data/en/' . $npcType;
			$filesList = $this->scanDir($npcsDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$data = $this->getJSON($npcsDir . '/' . $file)[$npcType];

				$npcId = $npcsMap[$data['id']] ?? $newNpcId++;
				$npcsMap[$data['id']] = $npcId;

				$level = $data['lvl'] ?? null;
				if (is_string($level))
					$level = preg_replace('/^(\d+).*$/', '$1', $level); // Parse it down to just the starting level
				if ( ! is_numeric($level))
					$level = null;

				$npcsData[] = [
					/* id */	$npcId,
					/* enemy */	$npcType == 'mob' ? 1 : 0,
					/* level */ $level,
				];

				$npcTranslationsData[] = [
					/* npc_id */	$npcId,
					/* locale */	'en',
					/* name */		$this->clean($data['name']),
				];

				$coordinateEntry = false;

				// Add a coordinate, only interested in one
				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$coordinateEntry = [
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* coordinate_id */		$npcId,
						/* coordinate_type */	'npc', // See AppServiceProvider's MorphMap
						/* x */					$data['coords'][0] ?? null,
						/* y */					$data['coords'][1] ?? null,
						/* z */					null,
						/* radius */			null,
					];

				if ($coordinateEntry)
					$npcCoordinatesData[] = $coordinateEntry;

				// Only NPCs have "details" and "shops"
				if ($npcType == 'npc')
				{
					// NPC Details
					$details = [];

					if (isset($data['title']))
						$details['title'] = $data['title'];

					foreach ($npcFeatures as $feature)
						if (isset($data['appearance'][$feature]))
							$details[$feature] = $data['appearance'][$feature];

					if ($details)
						$npcDetailsData[] = [
							/* detailable_id */		$npcId,
							/* detailable_type */	'npc', // See AppServiceProvider's MorphMap
							/* data */				json_encode($details),
						];

					// NPC Shops
					if (isset($data['shops']))
					{
						foreach ($data['shops'] as $key => $shop)
						{
							// Shops don't have an ID
							// To unique them, md5 the shop contents
							$shopIdentifier = md5(serialize($shop));
							$shopId = $shopsMap[$shopIdentifier] ?? $newShopId++;
							$shopsMap[$shopIdentifier] = $shopId;

							$shopsData[] = [
								/* id */	$shopId,
							];

							$npcShopData[] = [
								/* npc_id */	$npcId,
								/* shop_id */	$shopId,
							];

							// Only EN translation available
							$shopTranslationsData[] = [
								/* shop_id */	$shopId,
								/* locale */	'en',
								/* name */		$this->clean($shop['name']),
							];

							if ($coordinateEntry)
								$shopCoordinatesData[] = [
									/* zone_id */			$coordinateEntry[0],
									/* coordinate_id */		$shopId,
									/* coordinate_type */	'shop', // See AppServiceProvider's MorphMap
									/* x */					$coordinateEntry[3],
									/* y */					$coordinateEntry[4],
									/* z */					$coordinateEntry[5],
									/* radius */			$coordinateEntry[6],
								];
						}
					}
				}
				elseif ($npcType == 'mob')
				{
					// Mob Details
					$details = [];

					if (isset($data['level']))
						$details['level'] = $data['level'];

					if ($details)
						$npcDetailsData[] = [
							/* detailable_id */		$npcId,
							/* detailable_type */	'npc', // See AppServiceProvider's MorphMap
							/* data */				json_encode($details),
						];
				}

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('npcs', $npcsData);
		$this->write('npc_translations', $npcTranslationsData);

		$this->write('npc_coordinates', $npcCoordinatesData);
		$this->write('npc_details', $npcDetailsData);

		$this->write('shops', $shopsData);
		$this->write('shop_translations', $shopTranslationsData);
		$this->write('npc_shop', $npcShopData);

		$this->write('shop_coordinates', $shopCoordinatesData);

		$this->saveMap('npcs', $npcsMap);
		$this->saveMap('shops', $shopsMap);
	}

	protected function objectives()
	{
		// Objective Ids vary wildly, convert them
		$objectivesMap = $this->map['objectives'] ?? [];

		// Set up the columns as the first row of data
		$objectivesData = [
			[ 'id', 'niche_id', 'issuer_id', 'target_id', 'type', 'repeatable', 'level', ],
		];
		$objectiveTranslationsData = [
			[ 'objective_id', 'locale', 'name', 'description', ],
		];
		$objectiveCoordinatesData = [
			[ 'zone_id', 'coordinate_id', 'coordinate_type', 'x', 'y', 'z', 'radius', ],
		];
		$objectiveDetailsData = [
			[ 'detailable_id', 'detailable_type', 'data', ],
		];
		$itemObjectiveData = [
			[ 'item_id', 'objective_id', 'reward', 'quantity', 'quality', 'rate', ],
		];

		$newObjectiveId = empty($objectivesMap) ? 1 : (max($objectivesMap) + 1);

		// The order of these are specific, not just alphabetical; see ffxiv game config file if you want to change this
		foreach (['achievement', 'fate', 'leve', 'quest'] as $objectiveTypeId => $objectiveType)
		{
			echo 'Starting ' . $objectiveType . PHP_EOL;

			$objectiveDir = $this->originDataLocation . 'data/en/' . $objectiveType;
			$filesList = $this->scanDir($objectiveDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$jsonData = $this->getJSON($objectiveDir . '/' . $file);
				$data = $jsonData[$objectiveType];

				$objectiveId = $objectivesMap[$objectiveType . $data['id']] ?? $newObjectiveId++;
				$objectivesMap[$objectiveType . $data['id']] = $objectiveId;

				$issuerId = isset($data['issuer']) ? $this->map['npcs'][$data['issuer']] : null;

				$levemeteId = isset($data['levemete']) ? $this->map['npcs'][$data['levemete']] : false; // false to trickle into targetId
				$targetId = isset($data['target']) ? $this->map['npcs'][$data['target']] : null;

				$objectivesData[] = [
					/* id */			$objectiveId,
					/* niche_id */		$data['jobCategory'] ?? null,
					/* issuer_id */		$issuerId,
					/* target_id */		$levemeteId ?? $targetId,
					/* type */			$objectiveTypeId,
					/* repeatable */	in_array($objectiveType, ['fate', 'leve']),
					/* level */			$data['lvl'] ?? null,
				];

				// Only EN available
				$objectiveTranslationsData[] = [
					/* objective_id */	$objectiveId,
					/* locale */		'en',
					/* name */			$this->clean($data['name'] ?? null),
					/* description */	$this->clean($data['description'] ?? null),
				];

				$details = null;

				if ($objectiveType == 'achievement')
					$details['category'] = $this->map['core']['achievementCategoryIndex'][$data['category']]['kind'] . '/' . $this->map['core']['achievementCategoryIndex'][$data['category']]['name'];

				if ($objectiveType == 'fate' || $objectiveType == 'leve')
					foreach ([ 'xp', 'gil', 'plate', 'frame', 'areaicon' ] as $feature)
						if (isset($data[$feature]))
							$details[$feature] = $data[$feature];

				if ($objectiveType == 'quest')
				{
					// Genre, eventIcon, xp reward

					if (isset($data['reward']) && isset($data['reward']['xp']))
						$details['xp'] = $data['reward']['xp'];
					if (isset($data['eventIcon']));
						$details['eventIcon'] = $data['eventIcon'];

					$genreData = $this->map['core']['questGenreIndex'][$data['genre']];

					$details['genre'] = $genreData['section'] . '/' . $genreData['name'];
				}

				if ($details)
					$objectiveDetailsData[] = [
						/* detailable_id */		$objectiveId,
						/* detailable_type */	'objective', // See AppServiceProvider's MorphMap
						/* data */				json_encode($details),
					];

				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$objectiveCoordinatesData[] = [
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* coordinate_id */		$objectiveId,
						/* coordinate_type */	'objective', // See AppServiceProvider's MorphMap
						/* x */					$data['coords'][0] ?? null,
						/* y */					$data['coords'][1] ?? null,
						/* z */					null,
						/* radius */			null,
					];

				if (in_array($objectiveType, ['leve', 'quest']) && isset($data['requires']))
					foreach ($data['requires'] as $requirement)
						$itemObjectiveData[] = [
							/* item_id */		$requirement['item'],
							/* objective_id */	$objectiveId,
							/* reward */		false,
							/* quantity */		$requirement['amount'] ?? null,
							/* quality */		null,
							/* rate */			null,
						];

				if ($objectiveType == 'leve' && isset($data['rewards']))
					// Rewards data is actually stored next to the $type data, so call back to the JSON
					foreach ($jsonData['rewards']['entries'] as $entry)
						$objectiveItemRewardData[] = [
							/* item_id */		$entry['item'],
							/* objective_id */	$objectiveId,
							/* reward */		true,
							/* quantity */		$entry['amount'] ?? null,
							/* quality */		null,
							/* rate */			$entry['rate'] * 100, // Rate is "0.05", make it "5"
						];

				if ($objectiveType == 'quest' && isset($data['reward']) && isset($data['reward']['items']))
					foreach ($data['reward']['items'] as $reward)
						$objectiveItemRewardData[] = [
							/* item_id */		$reward['id'],
							/* objective_id */	$objectiveId,
							/* reward */		true,
							/* quantity */		$reward['num'] ?? null,
							/* quality */		null,
							/* rate */			null,
						];

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('objectives', $objectivesData);
		$this->write('objective_translations', $objectiveTranslationsData);
		$this->write('objective_coordinates', $objectiveCoordinatesData);
		$this->write('objective_details', $objectiveDetailsData);
		$this->write('item_objective', $itemObjectiveData);

		$this->saveMap('objectives', $objectivesMap);
	}

	protected function nodes()
	{
		// Node Ids are shared, convert them to one
		$nodesMap = $this->map['nodes'] ?? [];

		// Set up the columns as the first row of data
		$nodesData = [
			[ 'id', 'level', 'type', ],
		];
		$nodeTranslationsData = [
			[ 'node_id', 'locale', 'name', ],
		];
		$nodeCoordinatesData = [
			[ 'zone_id', 'coordinate_id', 'coordinate_type', 'x', 'y', 'z', 'radius', ],
		];
		$nodeDetailsData = [
			[ 'detailable_id', 'detailable_type', 'data', ],
		];
		$itemNodeData = [
			[ 'node_id', 'item_id', ],
		];

		// Nodes cover both physical nodes and fishing nodes
		$newNodeId = empty($nodesMap) ? 1 : (max($nodesMap) + 1);

		// Also handle mobs, which are basically npcs
		foreach (['node', 'fishing'] as $nodeType)
		{
			if ($nodeType == 'fishing')
				echo 'Starting fishing' . PHP_EOL;

			$nodeDir = $this->originDataLocation . 'data/en/' . $nodeType;
			$filesList = $this->scanDir($nodeDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$data = $this->getJSON($nodeDir . '/' . $file)[$nodeType];

				$nodeId = $nodesMap[$nodeType . $data['id']] ?? $newNodeId++;
				$nodesMap[$nodeType . $data['id']] = $nodeId;

				if (isset($data['category']))
					$data['type'] = 10 + $data['category'];

				$nodesData[] = [
					/* id */	$nodeId,
					/* level */ $data['lvl'],
					/* type */	$data['type'],
				];

				// Only EN translation available
				$nodeTranslationsData[] = [
					/* node_id */	$nodeId,
					/* locale */	'en',
					/* name */		$this->clean($data['name'] ?? null),
				];

				foreach ($data['items'] as $item)
					$itemNodeData[] = [
						/* node_id */	$nodeId,
						/* item_id' */	$item['id'],
					];

				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$nodeCoordinatesData[] = [
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* coordinate_id */		$nodeId,
						/* coordinate_type */	'node', // See AppServiceProvider MorphMap
						/* x */					$data['x'] ?? ($data['coords'][0] ?? null),
						/* y */					$data['y'] ?? ($data['coords'][1] ?? null),
						/* z */					null,
						/* radius */			$data['radius'] ?? null,
					];

				$details = [];

				foreach ([ 'limited', 'limitType', 'uptime', 'stars', ] as $feature)
					if (isset($data[$feature]))
						$details[$feature] = $data[$feature];

				if (isset($data['time']))
					$details['appears'] = $data['time'];

				if ($details)
					$nodeDetailsData[] = [
						/* detailable_id */		$nodeId,
						/* detailable_type */	'node', // See AppServiceProvider MorphMap
						/* data */				json_encode($details),
					];

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('nodes', $nodesData);
		$this->write('node_translations', $nodeTranslationsData);
		$this->write('node_coordinates', $nodeCoordinatesData);
		$this->write('node_details', $nodeDetailsData);
		$this->write('item_node', $itemNodeData);

		$this->saveMap('nodes', $nodesMap);
	}

	protected function items()
	{
		// Node Ids are shared, convert them to one
		$pricesMap = $this->map['prices'] ?? [];

		// Set up the columns as the first row of data
		$itemsData = [
			[ 'id', 'category_id', 'ilvl', 'icon', 'rarity', ],
		];
		$itemTranslationsData = [
			[ 'item_id', 'locale', 'name', 'description', ],
		];
		$itemCoordinatesData = [
			[ 'zone_id', 'coordinate_id', 'coordinate_type', 'x', 'y', 'z', 'radius', ],
		];
		$equipmentData = [
			[ 'item_id', 'niche_id', 'slot', 'level', 'sockets', ],
		];
		$attributeItemData = [
			[ 'attribute_id', 'item_id', 'quality', 'value', ],
		];
		$itemNpcData = [
			[ 'item_id', 'npc_id', 'rate', ],
		];
		$priceData = [
			[ 'quality', 'item_id', 'market', 'amount' ],
		];
		$itemPriceData = [
			[ 'item_id', 'price_id', ],
		];
		$recipeData = [
			[ 'id', 'item_id', 'job_id', 'level', 'sublevel', 'yield', 'quality', 'chance', ],
		];
		$itemRecipeData = [
			[ 'item_id', 'recipe_id', 'quantity', ],
		];
		// "Fixed" Data - Non numeric IDs skipped

		// Keep pricing unique
		$newPriceId = empty($pricesMap) ? 1 : (max($pricesMap) + 1);

		$itemDir = $this->originDataLocation . 'data/%s/item';
		$filesList = $this->scanDir(sprintf($itemDir, 'en'));
		$lastKey = count($filesList) - 1;

		foreach ($filesList as $key => $file)
		{
			$json = $this->getJSON($itemDir . '/' . $file, 'item', config('translatable.locales'));
			$data = $json['item'];

			if ( ! is_numeric($data['id']))
				continue;

			$itemId = $data['id'];

			$itemsData[] = [
				/* id */			$itemId,
				/* category_id */	$data['category'] ?? null,
				/* ilvl */			$data['ilvl'] ?? null,
				/* icon */			$data['icon'] ?? null,
				/* rarity */		$data['rarity'] ?? null,
			];

			// Translatable Data
			if (isset($json['locales']))
				foreach ($json['locales'] as $locale => $localeData)
					$itemTranslationsData[] = [
						/* item_id */		$itemId,
						/* locale */		$locale,
						/* name */			$this->clean($localeData['name'] ?? $data['name'] ?? null),
						/* description */	$this->clean($localeData['description'] ?? $data['description'] ?? null),
					];

			// Equipment - If a slot is defined, it's equipment
			if (isset($data['slot']))
				$equipmentData[] = [
					/* item_id */	$itemId,
					/* niche_id */	$data['jobs'],
					/* slot */		$data['slot'],
					/* level */		$data['elvl'],
					/* sockets */	$data['sockets'] ?? null,
				];

			// Item Attributes
			// We have $this->map['attributes'] to play with here from earlier
			if (isset($data['attr']))
			{
				// We rely on $sync having the keys of 0 for '' and 1 for '_hq'
				foreach (['', '_hq'] as $sync => $quality)
				{
					if ( ! isset($data['attr' . $quality]))
						continue;

					// attr or attr_hq
					$attributes = $data['attr' . $quality];

					// Normalize food/potion buffs
					if (isset($attributes['action']))
					{
						$normalizedAttributes = [];
						foreach ($attributes['action'] as $attribute => $definition)
							$normalizedAttributes[$attribute] = $definition['rate'];
						$attributes = $normalizedAttributes;
					}

					foreach ($attributes as $attributeName => $value)
						if ( ! is_null($value))
							$attributeItemData[] = [
								/* item_id */		$itemId,
								/* attribute_id */	$this->map['attributes'][$attributeName],
								/* quality */		null,
								/* value */			$value,
							];
				}
			}

			// Coordinates
			if (isset($data['instances']))
				foreach ($data['instances'] as $instanceId)
					$itemCoordinatesData[] = [
						/* zone_id */			$this->fixInstanceId($instanceId),
						/* coordinate_id */		$itemId,
						/* coordinate_type */	'item', // See AppServiceProvider's MorphMap
						/* x */					null,
						/* y */					null,
						/* z */					null,
						/* radius */			null,
					];

			// Populate: item_npc
			// Populate: item_price
			foreach (['vendors', 'tradeShops'] as $vendorType)
				if (isset($data[$vendorType]))
					foreach ($data[$vendorType] as $vendorData)
					{
						$vendorItemId = null;

						if ($vendorType == 'tradeShops')
						{
							$vendorItemId = $vendorData['listings'][0]['currency'][0]['id'];
							$data['price'] = $vendorData['listings'][0]['currency'][0]['amount'];
						}

						// Key order is important: 0/false is for selling, 1/true is for buying
						foreach (['sell_price', 'price'] as $market => $value)
						{
							if ( ! isset($data[$value]))
								continue;

							$pricing = [
								/* quality */		null,
								//					// You can only buy with alternate currency, not sell
								/* item_id */		$market ? $vendorItemId : null,
								/* market */		$market,
								/* amount */		$data[$value],
							];

							$pricingIdentifier = md5(serialize($pricing));

							if ( ! isset($pricesMap[$pricingIdentifier]))
							{
								$priceData[] = $pricing;

								$priceId = $newPriceId++;
								$pricesMap[$pricingIdentifier] = $priceId;
							}
							else
								$priceId = $pricesMap[$pricingIdentifier];

							$itemPriceData[] = [
								/* item_id */	$itemId,
								/* price_id */	$priceId,
							];
						}
					}

			// Populate: recipes
			// Populate: recipe_ingredients
			if (isset($data['craft']))
				foreach ($data['craft'] as $craft)
				{
					$recipeData[] = [
						/* id */		$craft['id'],
						/* item_id */	$itemId,
						/* job_id */	$this->fixJobId($craft['job']),
						/* level */		$craft['lvl'],
						/* sublevel */	$craft['stars'] ?? null,
						/* yield */		$craft['yield'] ?? null,
						/* quality */	$craft['hq'] ?? null,
						/* chance */	null,
					];

					foreach ($craft['ingredients'] as $ingredient)
						$itemRecipeData[] = [
							/* item_id */	$itemId,
							/* recipe_id */	$craft['id'],
							/* quantity */	$ingredient['amount'],
						];
				}

			// Item Drops
			if (isset($data['drops']))
				foreach ($data['drops'] as $npcId)
					if (isset($this->map['npcs'][$npcId]))
						$itemNpcData[] = [
							/* item_id */	$itemId,
							/* npc_id */	$npcId,
							/* rate */		null,
						];

			// Only update progress every 5 steps
			if ($key % 5 == 0)
				$this->progress($key, $lastKey);
		}

		$this->progress($key, $lastKey, true);

		$this->write('items', $itemsData);
		$this->write('item_translations', $itemTranslationsData);
		$this->write('item_coordinates', $itemCoordinatesData);
		$this->write('equipment', $equipmentData);
		$this->write('attribute_item', $attributeItemData);
		$this->write('item_npc', $itemNpcData);
		$this->write('item_price', $itemPriceData);
		$this->write('recipes', $recipeData);
		$this->write('item_recipe', $itemRecipeData);

		$this->saveMap('prices', $pricesMap);
	}

	/**
	 * Helper Functions
	 */

	protected function fixInstanceId($id)
	{
		// Zones have IDs ranging from 1 to ~3000 (as of today)
		// Instances have mostly ids of 20000+, however there are a handful that are less than 10000.
		// Adding 100k to the id to "namespace" them away from zone ids
		return $id + 100000;
	}

	protected function fixJobId($id)
	{
		// The Adventurer has an ID of 0, but change it to 255
		return $id == 0 ? 255 : $id;
	}

}
