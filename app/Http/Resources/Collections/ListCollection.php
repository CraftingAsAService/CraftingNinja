<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCollection extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$list = [
			'id' => $this->id,
			'name' => $this->name,
			'public' => $this->public,
			'description' => $this->description,
		];

		return $list;
	}
}
