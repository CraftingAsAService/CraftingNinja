<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'          => $this->id,
			'name'        => $this->product->name,
			'ilvl'        => $this->product->ilvl,
			'icon'        => $this->product->icon,
			'rarity'      => $this->product->rarity,
			'category'    => $this->product->category ? $this->product->category->name : '',
			'level'       => $this->level,
			'sublevel'    => $this->sublevel,
			'yield'       => $this->yield,
			'quality'     => $this->quality,
			'chance'      => $this->chance,
			'job'         => $this->job ? [
				'id'   => $this->job->id,
				'icon' => $this->job->icon,
			] : null,
			'ingredients' => $this->ingredients->map(function() {
				return [
					'name'   => $this->name,
					'ilvl'   => $this->ilvl,
					'icon'   => $this->icon,
					'rarity' => $this->rarity,
				];
			}),
		];
	}
}
