<?php

namespace App\Models\Game\Concepts;

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

}
