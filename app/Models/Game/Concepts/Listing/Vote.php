<?php

namespace App\Models\Game\Concepts\Listing;

use App\Models\Game\Concept;
use App\Models\Game\Concepts\Listing;
use App\Models\User;

class Vote extends Concept
{

	public $timestamps = true;

	/**
	 * Scopes
	 */

	public function scopeFromUser($query, $userId = null)
	{
		if ( ! $userId && ! auth()->check())
			return $query;

		return $query->where('user_id', $userId ?? auth()->user()->id);
	}

	/**
	 * Relationships
	 */

	public function listing()
	{
		return $this->belongsTo(Listing::class)->withTranslation();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
