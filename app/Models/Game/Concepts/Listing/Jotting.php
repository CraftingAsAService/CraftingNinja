<?php

namespace App\Models\Game\Concepts\Listing;

use App\Models\Game\Concepts\Listing;

class Jotting extends \App\Models\Game\Concept
{

	protected $table = 'listing_jottings';

	public $timestamps = false;

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

	public function jottable()
	{
		return $this->morphTo();
	}

}
