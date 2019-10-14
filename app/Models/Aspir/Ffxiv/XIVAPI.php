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

		$this->loopEndpoint('leve', $apiFields, function($data) use ($idAdditive) { {
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
				'type'       => null, // Filled in later
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

	}

	/**
	 * Helper Functions
	 */

	private function addLanguageFields(&$array, $sprintfableString) {
		foreach ($this->xivapiLanguages as $lang)
			array_push($array, sprintf($sprintfableString, $lang));
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
