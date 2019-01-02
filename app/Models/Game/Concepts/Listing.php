<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Listing\Jotting;
use App\Models\Game\Concepts\Listing\Vote;
use App\Models\User;
use Carbon\Carbon;
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

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Functions
	 */

	public function publish($publish = true)
	{
		if ($this->user_id == auth()->user()->id)
		{
			$this->published_at = $publish ? Carbon::now() : null;
			$this->save();
		}

		return $this;
	}

	public function unpublish()
	{
		return $this->publish(false);
	}

}
