<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Collections\Item as ItemResource;

class Items extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return ItemResource::collection($this->collection);
	}
}
