<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$item = [
			'id'       => $this->id,
			'name'     => $this->name,
			'icon'     => $this->icon,
			'rarity'   => $this->rarity,
			'ilvl'     => $this->ilvl,
			'category' => $this->category ? $this->category->name : '',
		];

		if ($this->recipes->count())
			$item['recipes'] = $this->recipes->map(function($recipe) {
				$job = $recipe->job ? [
					'id'   => $recipe->job->id,
					'icon' => $recipe->job->icon
				] : null;

				return [
					'id'       => $recipe->id,
					'level'    => $recipe->level,
					'sublevel' => $recipe->sublevel,
					'job'      => $job,
				];
			});

		if ($this->equipment)
		{
			$jobs = $this->equipment->niche && $this->equipment->niche->jobs ? $this->equipment->niche->jobs->map(function($job) {
					return [
						'id'   => $job->id,
						'icon' => $job->icon
					];
				}) : null;

			$item['equipment'] = [
				'id'      => $this->equipment->id,
				'level'   => $this->equipment->level,
				'slot'    => $this->equipment->slot,
				'sockets' => $this->equipment->sockets,
				'jobs'    => $jobs,
			];
		}

		return $item;
	}
}
