<?php

/**
 * XIVAPI
 * 	Get data from XIVAPI
 */

namespace App\Models\Aspir\Ffxiv;

use Cache;

trait XIVAPI
{

	public $limit = null;
	public $chunkLimit = null;

	public function xivapiSetup()
	{
		$this->api = new \XIVAPI\XIVAPI();
		$this->api->environment->key(config('services.xivapi.key'));
	}

	public function achievements()
	{
		$this->loopEndpoint('achievement', [
			'ID',
			'Name',
			'ItemTargetID',
			'IconID',
		], function($data) {
			dd($data);
			// We only care about achievements that provide an item
			if ( ! $data->ItemTargetID)
				return;

			$this->aspir->setData('achievement', [
				'id'      => $data->ID,
				'name'    => $data->Name,
				'item_id' => $data->ItemTargetID,
				'icon'    => $data->IconID,
			], $data->ID);
		});
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
