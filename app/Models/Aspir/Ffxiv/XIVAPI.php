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

		foreach ($this->xivapiLanguages as $lang)
		{
			array_push($apiFields, 'Name_' . $lang);
			array_push($apiFields, 'Description_' . $lang);
		}

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
			], $data->ID);

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

			dd($this->data);
		});
	}

	protected function fates($idAdditive)
	{

	}

	protected function leves($idAdditive)
	{

	}

	protected function quests($idAdditive)
	{

	}

	/**
	 * Helper Functions
	 */

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
