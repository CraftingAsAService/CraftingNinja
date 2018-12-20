<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing\Jotting;
use App\Models\Game\Concepts\Listing\Vote;
use Dimsav\Translatable\Translatable;

class Listing extends \App\Models\Game\Concept
{

	use Translatable;

	public $translationModel = \App\Models\Game\Translations\ListingTranslation::class;
	public $translatedAttributes = [ 'name', 'description' ];

	public $timestamps = true;

	protected $dates = [
		'published_at',
	];

	/**
	 * Scopes
	 */

	public function scopeFromUser($query, $userId = null)
	{
		return $query->where('user_id', $userId ?? auth()->user()->id);
	}

	/**
	 * Public Only scope
	 */
	public function scopePublished($query)
	{
		return $query->whereNotNull('published_at');
	}

	/**
	 * Relationships
	 */

	public function jottings()
	{
		return $this->hasMany(Jotting::class);
	}

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}

}
