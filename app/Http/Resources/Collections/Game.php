<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\JsonResource;

class Game extends JsonResource
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
			'name' => $this->name,
			'slug' => $this->slug,
			'abbreviation' => $this->abbreviation,
			'version' => $this->version,
			'description' => $this->description,
		];
	}
}
