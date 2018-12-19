<?php

namespace App\Models\Game\Concepts;

class Lists extends \App\Models\Game\Concept
{

	public $timestamps = true;

	protected $casts = [
		'contents' => 'json',
	];

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot()
	{
		parent::boot();

		// Auto set value(s) on creation
		static::creating(function($query) {
			$query->user_id = auth()->user()->id;
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
	 * Public Only scope
	 * @param  boolean $switch true will only return public results
	 *                         anything else returns all results
	 */
	public function scopePublicOnly($query, $switch = true)
	{
		if ( ! $switch)
			return $query;

		return $query->where('public', 0);
	}

	public function scopeUsersLastEntry($query)
	{
		return $query->fromUser()->orderBy('created_at', 'desc');
	}

	/**
	 * Relationships
	 */

}
