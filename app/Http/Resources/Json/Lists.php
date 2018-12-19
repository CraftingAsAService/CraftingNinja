<?php

namespace App\Http\Resources\Json;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Collections\ListCollection as ListResource;

class Lists extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return ListResource::collection($this->collection);
	}
}
