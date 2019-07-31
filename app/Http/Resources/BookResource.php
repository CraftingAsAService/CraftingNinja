<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// a make:resource BookResource

class BookResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$user = $this->user ? [
			'id'   => $this->user->id,
			'name' => $this->user->name,
		] : null;
		$job = $this->job ? [
			'id'   => $this->job->id,
			'icon' => $this->job->icon,
		] : null;

		return [
			'id'           => $this->id,
			'name'         => $this->name,
			'description'  => $this->description,
			'user'         => $user,
			'job'          => $job,
			'min_level'    => $this->min_level,
			'max_level'    => $this->max_level,
			'my_vote'      => $this->myVote ? true : false,
			'votes'        => $this->votes_count ?? 0,
			'updated_at'   => $this->updated_at,
			'last_updated' => $this->updated_at->diffForHumans(),
		];
	}
}
