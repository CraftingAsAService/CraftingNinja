<?php

namespace App\Models\Game\Concepts\Listing;

use App\Models\Game\Concepts\Listing;
use App\Models\User;

class Vote extends \App\Models\Game\Concept
{

	protected $table = 'listing_votes';

	public $timestamps = true;

	/**
	 * Scopes
	 */

	/**
	 * Relationships
	 */

	public function listing()
	{
		return $this->belongsTo(Listing::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
