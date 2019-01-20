<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concept;
use App\Models\Game\Concepts\Listing\Vote;
use App\Models\Game\Concepts\Report;
use App\Models\Translations\ListingTranslation;
use App\Models\User;
use Carbon\Carbon;
use Dimsav\Translatable\Translatable;

class Listing extends Concept
{

	use Translatable;

	public $translationModel = ListingTranslation::class;
	public $translatedAttributes = [ 'name', 'description' ];

	public $timestamps = true;

	protected $dates = [
		'published_at',
	];

	public static $polymorphicRelationships = [
		'i' => 'items',
		'o' => 'objectives',
		'r' => 'recipes',
		'n' => 'nodes',
	];

	// public static function boot()
	// {
	// 	parent::boot();

	// 	// Auto assign the user_id to the auth user
	// 	static::creating(function ($model) {
	// 		dd($model);
	// 		$model->user_id = auth()->user()->id;
	// 	});
	// }

	/**
	 * Scopes
	 */

	public function scopeFilter($query, $filters)
	{
		if (isset($filters['class']))
			$query->whereIn('job_id', is_array($filters['class']) ? $filters['class'] : explode(',', $filters['class']));

		if (isset($filters['level']))
			$query->where('min_level', '<=', $filters['level'])
					->where('max_level', '>=', $filters['level']);

		return $query;
	}

	public function scopeFromUser($query, $userId = null)
	{
		return $query->where('user_id', $userId ?? auth()->user()->id);
	}

	public function scopePublished($query)
	{
		return $query->whereNotNull('published_at');
	}

	public function scopeUnpublished($query)
	{
		return $query->whereNull('published_at');
	}

	public function scopeActive($query, $userId = null)
	{
		return $query->fromUser($userId)->unpublished();
	}

	/**
	 * Relationships
	 */

	public function job()
	{
		return $this->belongsTo(Job::class)->withTranslation();
	}

	public function votes()
	{
		return $this->hasMany(Vote::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function jottings()
	{
		return $this->morphTo();
	}

	public function items()
	{
		return $this->morphedByMany(Item::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	public function objectives()
	{
		return $this->morphedByMany(Objective::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	public function recipes()
	{
		return $this->morphedByMany(Recipe::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	public function nodes()
	{
		return $this->morphedByMany(Node::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	public function reports()
	{
		return $this->morphMany(Report::class, 'reportable');
	}

	/**
	 * Functions
	 */

	public function isPublished()
	{
		return $this->published_at !== null;
	}

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
