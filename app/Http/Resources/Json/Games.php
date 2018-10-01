<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Collections\Game as GameResource;

class Games extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return GameResource::collection($this->collection);
	}
}
