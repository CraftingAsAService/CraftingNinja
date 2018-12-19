<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;

class Book extends \App\Models\Game\Concept
{

	public $timestamps = true;

	protected $casts = [
		'contents' => 'json',
	];

	protected $guarded = [ 'id' ];

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot()
	{
		parent::boot();

		// Auto set value(s) on creation
		static::creating(function($query) {
			// $query->user_id = auth()->user()->id;
			$query->locale = app()->getLocale();
		});
	}

	/**
	 * Scopes
	 */

	public function scopeFromUser($query, $userId = null)
	{
		return $query->where('user_id', $userId ?? auth()->user()->id);
	}

	public function scopeMatchLocale($query, $locale = null)
	{
		return $query->where('locale', $locale ?? app()->getLocale());
	}

	/**
	 * Relationships
	 */

	public function items()
	{
		return $this->belongsToMany(Item::class)->withPivot('quantity');
	}
}
