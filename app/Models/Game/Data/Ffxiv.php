<?php

/**
 * php artisan osmose:parse ffxiv
 */

namespace App\Models\Game\Data;

use Illuminate\Database\Eloquent\Model;

class Ffxiv extends Model
{
	public $dataLocation = null,
			$core = null,
			$map = [];

	public function parse()
	{
		$this->dataLocation = env('DATA_REPOSITORY') . '/ffxiv/';

		$this->loadMaps();

		// GarlandTools core data
		$this->run('core');

		// categories
		// category_translations
		$this->run('categories');

		// attributes
		// attribute_translations
		$this->run('attributes');

		// jobs
		// job_translations
		$this->run('jobs');
		// job_groups
		$this->run('jobGroups');

		// zones
		// zone_translations
		$this->run('zones');

		// npcs
		// has: coordinates
		// has: details
		// npc_translations
		// npc_shop
		// shops
		// shop_translations
		// item_shop
		$this->run('npcs');

		// objective
		// has: coordinates
		// has: details
		// objective_translations
		// objective_item_required
		// objective_item_reward
		$this->run('objectives');

		// nodes
		// has: coordinates
		// has: details
		// node_rewards
		// node_translations
		$this->run('nodes');

		// items
		// has: coordinates
		// has: details
		// item_translations
		// equipment
		// item_attribute
		// item_npc
		// item_price
		// recipe_ingredients
		// recipe_translations
		// recipes
		$this->run('items');

		echo PHP_EOL . "Finished" . PHP_EOL;
	}

	private function run($action)
	{
		if (\Cache::has($action))
		{
			echo 'Skipping ' . $action . PHP_EOL;
			return;
		}

		echo 'Starting ' . $action . PHP_EOL;

		clock()->startEvent($action, $action);

		$this->$action();

		$timeline = clock()->endEvent($action);

		$duration = round(clock()->getTimeline()->toArray()[$action]['duration'] / 1000, 2);
		$memoryUsage = $this->humanReadable(memory_get_usage());
		echo PHP_EOL . $action . ' ⧖ ' . $duration . 's, ' . $memoryUsage . PHP_EOL . PHP_EOL;

		\Cache::put($action, true, 10080); // Store for 1 week
	}

	private function humanReadable($size)
	{
		$fileSizeNames = [" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB"];
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .$fileSizeNames[$i] : '0 Bytes';
	}

	private function write($filename, $array, $isMap = false)
	{
		$writeDir = $this->dataLocation . 'parsed/' . ($isMap ? 'mappings/' : '');
		echo 'Writing ' . $filename . '.json' . PHP_EOL;
		file_put_contents($writeDir . $filename . '.json', json_encode($this->deduplicate($array)));
	}

	private function loadMaps()
	{
		foreach (array_diff(scandir($this->dataLocation . 'parsed/mappings'), ['.', '..']) as $filename)
		{
			$action = str_replace('.json', '', $filename);
			$this->readMap($action);
		}
	}

	private function readMap($filename)
	{
		$readDir = $this->dataLocation . 'parsed/mappings/';
		$file = $readDir . $filename . '.json';
		if (is_file($file))
			$this->map[$filename] = json_decode(file_get_contents($file), true);
	}

	private function saveMap($action, $data)
	{
		$this->write($action, $data, true);
		$this->map[$action] = $data;
	}

	/**
	 * Table Functions
	 */

	private function core()
	{
		// Get the core, fix it up, split it into an array
		$coreFile = file_get_contents($this->dataLocation . 'core.js');
		$coreArray = array_diff(explode(PHP_EOL, $this->binaryFix($coreFile)), ['']);

		// Clean the core
		$core = [];
		foreach ($coreArray as $row)
		{
			// Key winds up as `gt.something.something`
			list($key, $value) = explode(' = ', $row);

			// Key Cleanup
			// Drop the `gt.`
			$key = preg_replace('/^gt\./', '', $key);
			// And any JS var declaration
			$key = preg_replace('/^var\s/', '', $key);
			// Make the [id] part of the key into a dot notation
			$key = preg_replace('/\[(.*)\]/', '.$1', $key);

			// Value Cleanup
			// Drop the semicolon
			$value = substr($value, 0, -1);
			if (in_array(substr($value, 0, 1), ['{', '[']))
				$value = json_decode($value, true);

			$core[$key] = $value;
		}

		$this->saveMap('core', $core);
	}

	private function attributes()
	{
		$attributesMap = [];
		// Set up the columns as the first row of data
		$attributesData = [
			[ 'id', ],
		];
		$attributeTranslationsData = [
			[ 'attribute_id', 'locale', 'name', ],
		];
		// "Fixed" Data - Attribute names had ucwords() ran against them

		$attributeId = 0;

		$itemsDir = $this->dataLocation . 'data/item';

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
						if (isset($attributesMap[$attributeName]))
							continue;

						// Attributes don't have an ID we can already use
						// Save the attribute down for later referential usage
						$attributesMap[$attributeName] = ++$attributeId;

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
		$this->write('attributeTranslations', $attributeTranslationsData);

		$this->saveMap('attributes', $attributesMap);
	}

	private function categories()
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

		$parentCategories = $this->getJSON($this->dataLocation . 'custom/categoryParents.json');
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

		$categories = array_values($this->map['core']['item.categoryIndex']);
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
		$this->write('categoryTranslations', $categoryTranslationsData);
	}

	private function jobs()
	{
		// $jobsMap unnecessary, reliable id
		// Set up the columns as the first row of data
		$jobsData = [
			[ 'id', 'type', 'tier', ],
		];
		$jobTranslationsData = [
			[ 'job_id', 'locale', 'name', 'abbreviation', ],
		];
		// "Fixed" Data - Any ID less than one was ignored

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
		$this->write('jobTranslations', $jobTranslationsData);
	}

	private function jobGroups()
	{
		// $jobGroupsMap unnecessary, reliable id
		// Set up the columns as the first row of data
		$jobGroupsData = [
			[ /*'id', */'group_id', 'job_id', ],
		];
		// Fixed Data - Any referenced job with an ID less than 1 is ignored

		$jobGroups = array_values($this->map['core']['jobCategories']);

		foreach ($jobGroups as $group)
		{
			foreach ($group['jobs'] as $key => $jobId)
			{
				if ($jobId < 1)
					continue;

				$jobGroupsData[] = [
					/* group_id */	$group['id'],
					/* job_id */	$this->fixJobId($jobId),
				];
			}
		}

		$this->write('jobGroups', $jobGroupsData);
	}

	private function zones()
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

		$zones = array_values($this->map['core']['location.index']);

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

		$instancesDir = $this->dataLocation . 'data/instance';
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
		$this->write('zoneTranslations', $zoneTranslationsData);
	}

	private function npcs()
	{
		// NPC Ids are unreliable, convert them
		$npcsMap = [];
		// Set up the columns as the first row of data
		$npcsData = [
			[ 'id', 'enemy', 'level', ],
		];
		$npcTranslationsData = [
			[ 'npc_id', 'locale', 'name', ],
		];
		$npcCoordinatesData = [
			[ 'coordinate_id', 'coordinate_type', 'zone_id', 'x', 'y', ]
		];
		$npcDetailsData = [
			[ 'details_id', 'details_type', 'details', ]
		];
		$npcShopData = [
			[ 'npc_id', 'shop_id', ]
		];
		$shopData = [
			[ 'id', ]
		];
		$shopTranslationsData = [
			[ 'shop_id', 'locale', 'name', ]
		];
		$shopItemData = [
			[ 'shop_id', 'item_id', ]
		];
		$mobItemData = [
			[ 'npc_id', 'item_id', ]
		];
		// $itemPriceData = [
		// 	[ 'quality', 'alt_currency', 'purchase_price', 'sale_price', ]
		// ];

		// The type of npc features I want to save down
		$npcFeatures = [ 'race', 'tribe', 'gender', 'skinColorCode', 'hairColorCode', ];

		$npcId = 0;

		// Also handle mobs, which are basically npcs
		foreach (['npc', 'mob'] as $npcType)
		{
			if ($npcType == 'mob')
				echo 'Starting mobs' . PHP_EOL;

			$npcsDir = $this->dataLocation . 'data/' . $npcType;
			$filesList = $this->scanDir($npcsDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$data = $this->getJSON($npcsDir . '/' . $file)[$npcType];

				$npcsMap[$data['id']] = ++$npcId;

				if ( ! isset($data['en']) && isset($data['name']))
					$data['en']['name'] = $data['name'];

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

				foreach (config('translatable.locales') as $locale)
					if (isset($data[$locale]))
						$npcTranslationsData[] = [
							/* npc_id */	$npcId,
							/* locale */	$locale,
							// Super rare that name isn't set for JA.  Just use EN name instead
							/* name */		$this->clean($data[$locale]['name'] ?? $data['en']['name']),
						];

				// Add a coordinate, only interested in one
				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$npcCoordinatesData[] = [
						/* coordinate_id */		$npcId,
						/* coordinate_type */	'App\Models\Game\Aspects\Npc',
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* x */					$data['coords'][0] ?? null,
						/* y */					$data['coords'][1] ?? null,
					];

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
							/* details_id */	$npcId,
							/* details_type */	'App\Models\Game\Aspects\Npc',
							/* details */		json_encode($details),
						];

					// NPC Shops
					if (isset($data['shops']))
					{
						foreach ($data['shops'] as $shop)
						{
							$shopData[] = [
								/* id */	$shop['id'],
							];

							$npcShopData[] = [
								/* npc_id */	$npcId,
								/* shop_id */	$shop['id'],
							];

							// Only EN translation available
							$shopTranslationsData[] = [
								/* shop_id */	$shop['id'],
								/* locale */	'en',
								/* name */		$this->clean($shop['name']),
							];

							// Sometimes entries is a flat array of numbers, but when additional currency is involved it's an array
							if ( ! is_numeric($shop['entries'][0]))
							{
								$entries = [];
								foreach ($shop['entries'] as $ware)
									// Not concerned about alternate currency here, it'll be handled in items()
									foreach ($ware['item'] as $info)
										$entries[] = $info['id'];

								$shop['entries'] = $entries;
							}

							foreach ($shop['entries'] as $itemId)
								$shopItemData[] = [
									/* shop_id */	$shop['id'],
									/* item_id */	$itemId,
								];
						}
					}
				}
				elseif ($npcType == 'mob')
				{
					if (isset($data['drops']) && count($data['drops']))
					{
						foreach ($data['drops'] as $itemId)
							$mobItemData[] = [
								/* npc_id */		$npcId,
								/* item_id */		$itemId,
							];
					}
				}

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('npcs', $npcsData);
		$this->write('npcTranslations', $npcTranslationsData);
		$this->write('npcCoordinates', $npcCoordinatesData);
		$this->write('npcDetails', $npcDetailsData);

		$this->write('mobItem', $mobItemData);
		$this->write('npcShop', $npcShopData);
		$this->write('shop', $shopData);
		$this->write('shopTranslations', $shopTranslationsData);
		$this->write('shopItem', $shopItemData);

		$this->saveMap('npcs', $npcsMap);
	}

	private function objectives()
	{
		// Set up the columns as the first row of data
		$objectivesData = [
			[ 'id', 'type', 'repeatable', 'level', 'job_group_id', 'issuer', 'target', ],
		];
		$objectiveTranslationsData = [
			[ 'objective_id', 'locale', 'name', 'description', ],
		];
		$objectiveCoordinatesData = [
			[ 'coordinate_id', 'coordinate_type', 'zone_id', 'x', 'y', ]
		];
		$objectiveDetailsData = [
			[ 'details_id', 'details_type', 'details', ]
		];
		$objectiveItemRequiredData = [
			[ 'objective_id', 'quality', 'item_id', 'quantity', ]
		];
		$objectiveItemRewardData = [
			[ 'objective_id', 'quality', 'item_id', 'quantity', 'rate', ]
		];

		// Objective Ids are varied, convert them
		$objectiveId = 0;

		// The order of these are specific, not just alphabetical; see ffxiv game config file if you want to change this
		foreach (['achievement', 'fate', 'leve', 'quest'] as $objectiveTypeId => $objectiveType)
		{
			echo 'Starting ' . $objectiveType . PHP_EOL;

			$objectiveDir = $this->dataLocation . 'data/' . $objectiveType;
			$filesList = $this->scanDir($objectiveDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$jsonData = $this->getJSON($objectiveDir . '/' . $file);
				$data = $jsonData[$objectiveType];

				++$objectiveId;

				$objectivesData[] = [
					/* id */			$objectiveId,
					/* type */			$objectiveTypeId,
					/* repeatable */	in_array($objectiveType, ['fate', 'leve']),
					/* level */			$data['lvl'] ?? null,
					/* job_group_id */	$data['jobCategory'] ?? null,
					/* issuer */		$data['issuer'] ?? null,
					/* target */		$data['levemete'] ?? ($data['target'] ?? null),
				];

				foreach (config('translatable.locales') as $locale)
					if (isset($data[$locale]))
						$objectiveTranslationsData[] = [
							/* objective_id */	$objectiveId,
							/* locale */		$locale,
							// Super rare that name isn't set for JA.  Just use EN name instead
							/* name */			$this->clean($data[$locale]['name'] ?? $data['en']['name']),
							/* description */	$this->clean($data[$locale]['description'] ?? null),
						];

				$details = null;

				if ($objectiveType == 'achievement')
					$details['category'] = $this->map['core']['achievement.categoryIndex'][$data['category']]['kind'] . '/' . $this->map['core']['achievement.categoryIndex'][$data['category']]['name'];

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

					$genreData = $this->map['core']['quest.genreIndex'][$data['genre']];

					$details['genre'] = $genreData['section'] . '/' . $genreData['name'];
				}

				if ($details)
					$objectiveDetailsData[] = [
						/* details_id */	$objectiveId,
						/* details_type */	'App\Models\Game\Aspects\Objective',
						/* details */		json_encode($details),
					];

				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$objectiveCoordinatesData[] = [
						/* coordinate_id */		$objectiveId,
						/* coordinate_type */	'App\Models\Game\Aspects\Objective',
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* x */					$data['coords'][0] ?? null,
						/* y */					$data['coords'][1] ?? null,
					];

				if ($objectiveType == 'leve' && isset($data['requires']))
					foreach ($data['requires'] as $requirement)
						$objectiveItemRequiredData[] = [
							/* objective_id */	$objectiveId,
							/* quality */		null,
							/* item_id */		$requirement['item'],
							/* quantity */		$requirement['amount'] ?? null,
						];

				if ($objectiveType == 'quest' && isset($data['requires']))
					foreach ($data['requires'] as $requirement)
						$objectiveItemRequiredData[] = [
							/* objective_id */	$objectiveId,
							/* quality */		null,
							/* item_id */		$requirement['item'],
							/* quantity */		$requirement['amount'] ?? null,
						];

				if ($objectiveType == 'leve' && isset($data['rewards']))
					// Rewards data is actually stored next to the $type data, so call back to the JSON
					foreach ($jsonData['rewards']['entries'] as $entry)
						$objectiveItemRewardData[] = [
							/* objective_id */	$objectiveId,
							/* quality */		null,
							/* item_id */		$entry['item'],
							/* quantity */		$entry['amount'] ?? null,
							/* rate */			$entry['rate'] * 100, // Rate is "0.05", make it "5"
						];

				if ($objectiveType == 'quest' && isset($data['reward']) && isset($data['reward']['items']))
					foreach ($data['reward']['items'] as $reward)
						$objectiveItemRewardData[] = [
							/* objective_id */	$objectiveId,
							/* quality */		null,
							/* item_id */		$reward['id'],
							/* quantity */		$reward['num'] ?? null,
							/* rate */			null,
						];

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('objectives', $objectivesData);
		$this->write('objectiveTranslations', $objectiveTranslationsData);
		$this->write('objectiveCoordinates', $objectiveCoordinatesData);
		$this->write('objectiveDetails', $objectiveDetailsData);
		$this->write('objectiveItemRequired', $objectiveItemRequiredData);
		$this->write('objectiveItemReward', $objectiveItemRewardData);
	}

	private function nodes()
	{
		// Set up the columns as the first row of data
		$nodesData = [
			[ 'id', 'type', 'level', ],
		];
		$nodeTranslationsData = [
			[ 'node_id', 'locale', 'name', ],
		];
		$nodeCoordinatesData = [
			[ 'coordinate_id', 'coordinate_type', 'zone_id', 'x', 'y', ]
		];
		$nodeDetailsData = [
			[ 'details_id', 'details_type', 'details', ]
		];
		$nodeRewardData = [
			[ 'node_id', 'item_id', ]
		];

		// Nodes cover both physical nodes and fishing nodes
		$nodeId = 0;

		// Also handle mobs, which are basically npcs
		foreach (['node', 'fishing'] as $nodeType)
		{
			if ($nodeType == 'fishing')
				echo 'Starting fishing' . PHP_EOL;

			$nodeDir = $this->dataLocation . 'data/' . $nodeType;
			$filesList = $this->scanDir($nodeDir);
			$lastKey = count($filesList) - 1;

			foreach ($filesList as $key => $file)
			{
				$data = $this->getJSON($nodeDir . '/' . $file)[$nodeType];

				++$nodeId;

				if (isset($data['category']))
					$data['type'] = 10 + $data['category'];

				$nodesData[] = [
					/* id */	$nodeId,
					/* type */	$data['type'],
					/* level */ $data['lvl'],
				];

				if (isset($data['name']))
					// Only EN translation available
					$nodeTranslationsData[] = [
						/* node_id */	$nodeId,
						/* locale */	'en',
						/* name */		$this->clean($data['name']),
					];
				else
					foreach (config('translatable.locales') as $locale)
						if (isset($data[$locale]))
							$nodeTranslationsData[] = [
								/* node_id */	$nodeId,
								/* locale */	$locale,
								// Super rare that name isn't set for JA.  Just use EN name instead
								/* name */		$this->clean($data[$locale]['name'] ?? $data['en']['name']),
							];

				foreach ($data['items'] as $item)
					$nodeRewardData[] = [
						/* node_id */	$nodeId,
						/* item_id' */	$item['id'],
					];

				if (isset($data['areaid']) || isset($data['zoneid']) || isset($data['instance']))
					$nodeCoordinatesData[] = [
						/* coordinate_id */		$nodeId,
						/* coordinate_type */	'App\Models\Game\Aspects\Node',
						/* zone_id */			isset($data['instance']) ? $this->fixInstanceId($data['instance']) : ($data['areaid'] ?? $data['zoneid']),
						/* x */					$data['x'] ?? ($data['coords'][0] ?? null),
						/* y */					$data['y'] ?? ($data['coords'][1] ?? null),
					];

				$details = [];

				foreach ([ 'limited', 'limitType', 'uptime', 'stars', 'radius', ] as $feature)
					if (isset($data[$feature]))
						$details[$feature] = $data[$feature];

				if ($details)
					$nodeDetailsData[] = [
						/* details_id */	$nodeId,
						/* details_type */	'App\Models\Game\Aspects\Node',
						/* details */		json_encode($details),
					];

				// Only update progress every 5 steps
				if ($key % 5 == 0)
					$this->progress($key, $lastKey);
			}

			$this->progress($key, $lastKey, true);
		}

		$this->write('nodes', $nodesData);
		$this->write('nodeTranslations', $nodeTranslationsData);
		$this->write('nodeCoordinates', $nodeCoordinatesData);
		$this->write('nodeDetails', $nodeDetailsData);
		$this->write('nodeReward', $nodeRewardData);
	}

	private function items()
	{
		// Set up the columns as the first row of data
		$itemsData = [
			[ 'id', 'category_id', 'ilvl', 'icon', 'rarity', ],
		];
		$itemTranslationsData = [
			[ 'item_id', 'locale', 'name', 'description', ],
		];
		$itemCoordinatesData = [
			[ 'coordinate_id', 'coordinate_type', 'zone_id', 'x', 'y', ],
		];
		// $itemDetailsData = [
		// 	[ 'details_id', 'details_type', 'details', ],
		// ];
		$equipmentData = [
			[ 'item_id', 'job_group_id', 'slot', 'level', 'sockets', ],
		];
		$itemAttributeData = [
			[ 'item_id', 'attribute_id', 'quality', 'value', ],
		];
		$itemNpcData = [
			[ 'item_id', 'npc_id', 'item_price_id', 'rate', ],
		];
		$itemPriceData = [
			[ 'quality', 'alt_currency', 'purchase_price', 'sale_price', ],
		];
		$recipeData = [
			[ 'id', 'item_id', 'job_id', 'level', 'sublevel', 'yield', 'quality', 'chance', ],
		];
		$recipeIngredientData = [
			[ 'item_id', 'recipe_id', 'quantity', ],
		];
		// "Fixed" Data - Non numeric IDs skipped

		// Keep things unique
		$itemPriceIds = [];
		$itemPriceId = 0;

		$itemDir = $this->dataLocation . 'data/item';
		$filesList = $this->scanDir($itemDir);
		$lastKey = count($filesList) - 1;

		foreach ($filesList as $key => $file)
		{
			$data = $this->getJSON($itemDir . '/' . $file)['item'];

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
			foreach (config('translatable.locales') as $locale)
				if (isset($data[$locale]))
					$itemTranslationsData[] = [
						/* item_id */		$itemId,
						/* locale */		$locale,
						// Super rare that name isn't set for JA.  Just use EN name instead
						/* name */			$this->clean($data[$locale]['name'] ?? $data['en']['name']),
						/* description */	$this->clean($data['en']['description'] ?? null),
					];

			// Equipment - If a slot is defined, it's equipment
			if (isset($data['slot']))
				$equipmentData[] = [
					/* item_id */		$itemId,
					/* job_group_id */	$data['jobs'],
					/* slot */			$data['slot'],
					/* level */			$data['elvl'],
					/* sockets */		$data['sockets'] ?? null,
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
							$itemAttributeData[] = [
								/* item_id */		$itemId,
								/* attribute_id */	$this->map['attributes'][ucwords($attributeName)],
								/* quality */		null,
								/* value */			$value,
							];
				}
			}

			// Coordinates
			if (isset($data['instances']))
				foreach ($data['instances'] as $instanceId)
					$itemCoordinatesData[] = [
						/* coordinate_id */		$itemId,
						/* coordinate_type */	'App\Models\Game\Aspects\Item',
						/* zone_id */			$this->fixInstanceId($instanceId),
						/* x */					null,
						/* y */					null,
					];

			// Populate: item_npc
			// Populate: item_price
			foreach (['vendors', 'tradeSources'] as $npcType)
				if (isset($data[$npcType]))
					foreach ($data[$npcType] as $npcId => $npcData)
					{
						if ($npcType == 'vendors')
							$npcId = $npcData;
						elseif ($npcType == 'tradeSources')
							$data['price'] = $npcData[0]['currency'][0]['amount'];

						$newItemPriceData = [
							/* quality */			null,
							/* alt_currency */		$npcType == 'tradeSources',
							/* purchase_price */	$data['price'],
							/* sale_price */		$data['sell_price'] ?? null,
						];
						$useItemPriceId = null;

						$itemPriceLookup = base64_encode(serialize($newItemPriceData));

						if (isset($itemPriceIds[$itemPriceLookup]))
							$useItemPriceId = $itemPriceIds[$itemPriceLookup];
						else
						{
							// Keep things unique
							$useItemPriceId = ++$itemPriceId;
							$itemPriceIds[$itemPriceLookup] = $useItemPriceId;

							$itemPriceData[] = $newItemPriceData;
						}

						$itemNpcData[] = [
							/* item_id */		$itemId,
							/* npc_id */		$this->map['npcs'][$npcId],
							/* item_price_id */	$useItemPriceId,
							/* rate */			null,
						];
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
						$recipeIngredientData[] = [
							/* item_id */	$itemId,
							/* recipe_id */	$craft['id'],
							/* quantity */	$ingredient['amount'],
						];
				}

			// Only update progress every 5 steps
			if ($key % 5 == 0)
				$this->progress($key, $lastKey);
		}

		$this->progress($key, $lastKey, true);

		$this->write('items', $itemsData);
		$this->write('itemTranslations', $itemTranslationsData);
		$this->write('itemCoordinates', $itemCoordinatesData);
		// $this->write('itemDetails', $itemDetailsData);
		$this->write('equipment', $equipmentData);
		$this->write('itemAttribute', $itemAttributeData);
		$this->write('itemNpc', $itemNpcData);
		$this->write('itemPrice', $itemPriceData);
		$this->write('recipes', $recipeData);
		$this->write('recipeIngredient', $recipeIngredientData);
	}

	/**
	 * Helper Functions
	 */

	private function fixInstanceId($id)
	{
		// Zones have IDs ranging from 1 to ~3000 (as of today)
		// Instances have mostly ids of 20000+, however there are a handful that are less than 10000.
		// Adding 100k to the id to "namespace" them away from zone ids
		return $id + 100000;
	}

	private function fixJobId($id)
	{
		// The Adventurer has an ID of 0, but change it to 255
		return $id == 0 ? 255 : $id;
	}

	private function deduplicate($array)
	{
		return array_map('unserialize', array_unique(array_map('serialize', $array)));
	}

	private function scanDir($dir)
	{
		$filesList = array_diff(scandir($dir), ['.', '..']);
		natsort($filesList);
		return array_values($filesList);
	}

	private function grepDir($dir, $pattern)
	{
		echo 'Grepping ' . $dir . PHP_EOL;
		exec('grep -rl \'' . $dir . '\' -e \'' . $pattern . '\'', $filesList);
		natsort($filesList);
		return array_values($filesList);
	}

	private function clean($string)
	{
		// Further deconvert some characters
		$string = str_replace('–', '-', $string);
		$string = str_replace("\r\n", ' ', $string);
		$string = preg_replace('/\<\/?Emphasis\>/', '', $string);
		$string = str_replace('<SoftHyphen/>', '-', $string);

		return $string;
	}

	private function getJSON($path)
	{
		$content = file_get_contents($path);

		// http://stackoverflow.com/questions/17219916/json-decode-returns-json-error-syntax-but-online-formatter-says-the-json-is-ok
		for ($i = 0; $i <= 31; ++$i)
			$content = str_replace(chr($i), "", $content);
		$content = str_replace(chr(127), "", $content);

		// This is the most common part
		$content = $this->binaryFix($content);

		$content = json_decode($content, true);

		return $content;
	}

	private function binaryFix($string)
	{
		// Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
		// here we detect it and we remove it, basically it's the first 3 characters
		if (0 === strpos(bin2hex($string), 'efbbbf'))
			$string = substr($string, 3);

		return $string;
	}

	protected $_spinners = ['|', '/', '-', '\\', '|', '/', '-', '\\'];

	/**
	 * This is used to echo the progress of a task on the command line.
	 * Pass in the current row that you are on and the number of rows that need to be processed and this will echo out
	 * a progress bar like this
	 *
	 * Progress: [-----------------\                                           ]
	 *
	 * It is possible to change the width of the bar by passing in an int as the $steps param, otherwise this default
	 * to 60
	 *
	 * Once the process is complete pass in the $last param as true to finish the the process bar
	 *
	 * @param      $totalDone - The number of rows that have been processed so far
	 * @param      $total     - The total number of rows to be processed
	 * @param bool $last      - If the process has been completed
	 * @param bool $steps     - How wide the process bar should be
	 */
	public function progress($totalDone, $total, $last = false, $steps = false)
	{
		if (PHP_SAPI != 'cli')
			return;

		$steps = $steps == false ? 40 : $steps;
		if ($last === true)
			$display = "Progress: [" . str_repeat('-', $steps + 1) . "]\r" . PHP_EOL;
		else {
			$toGo        = floor((1 - ($totalDone / $total)) * $steps);
			$progressBar = str_repeat('-', $steps - $toGo);
			$emptySpace  = str_repeat(' ', $toGo);
			$index       = $totalDone % 8;
			$display     = "Progress: [" . $progressBar . $this->_spinners[$index] . $emptySpace . "]\r";
		}

		echo $display;
	}

}