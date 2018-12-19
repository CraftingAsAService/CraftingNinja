<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
			'id' => $this->id,
			'name' => $this->name,
			'icon' => $this->icon,
			'rarity' => $this->rarity,
			'ilvl' => $this->ilvl,
			'category' => $this->category ? $this->category->name : '',
		];

		if ($this->recipes->count())
			$item['recipes'] = $this->recipes->map(function($recipe) {
				return [
					'id' => $recipe->id,
					'level' => $recipe->level,
					'sublevel' => $recipe->sublevel,
					'job' => [
						'id' => $recipe->job->id,
						'icon' => $recipe->job->icon
					],
				];
			});

		if ($this->equipment)
			$item['equipment'] = [
				'id' => $this->equipment->id,
				'level' => $this->equipment->level,
				'slot' => $this->equipment->slot,
				'sockets' => $this->equipment->sockets,
				'jobs' => $this->equipment->jobGroups->map(function($jobGroup) {
					return [
						'id' => $jobGroup->job->id,
						'icon' => $jobGroup->job->icon
					];
				}),
			];

		return $item;
	}
}
