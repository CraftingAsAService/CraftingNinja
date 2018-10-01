<?php

namespace App\Models\Game\Aspects;

class Recipe extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\RecipeTranslation::class;
	// public $translatedAttributes = ['name'];

	/**
	 * Scopes
	 */

	static public function scopeFilter($query, $filters)
	{
		$sorting = $filters['sorting'] ?? null;
		$ordering = $filters['ordering'] ?? null;

		// Take care of any filter that includes item data
		$itemFilters = ['name', 'ilvl', 'ilvlMin', 'ilvlMax'];
		if (count(array_intersect(array_keys($filters), $itemFilters)) > 0 || in_array($sorting, $itemFilters))
		{
			// Base items join
			$query->select('recipes.*')
				->join('items', 'recipes.item_id', '=', 'items.id')
				->groupBy('items.id');

			// Include translation data if it's the name
			if (isset($filters['name']) || $sorting == 'name')
				$query->join('item_translations as t', 't.item_id', '=', 'items.id')
					->where('locale', config('app.locale'));

			// Sorting by name or ilvl
			if ($sorting == 'name')
				$query->orderBy('t.name', $ordering);
			else if ($sorting == 'ilvl')
				$query->orderBy('items.ilvl', $ordering);

			// Filter by the name
			if (isset($filters['name']))
				$query->where('t.name', 'like', '%' . str_replace(' ', '%', $filters['name']) . '%');

			// Filter by the ilvl range
			$query->filterByLevelRange($filters, 'items.ilvl', 'ilvlMin', 'ilvlMax');
		}

		$query->filterByLevelRange($filters);
		$query->filterByValues($filters, ['sublevel']);

		// Filter by Recipe pieces
		if (isset($filters['rclass']))
			$query->filterByJobs(explode(',', $filters['rclass']));

		return $query;
	}

	public function scopeFilterByJobs($query, array $jobIds)
	{
		return $query->whereIn('job_id', $jobIds);
	}

	/**
	 * Accessors & Mutators
	 */

	/**
	 * Relationships
	 */

	public function item()
	{
		return $this->belongsTo(Item::class)->withTranslation();
	}

	public function ingredients()
	{
		return $this->hasMany(Item::class, 'recipe_ingredients')->withPivot('quantity');
	}

	public function job()
	{
		return $this->belongsTo(Job::class)->withTranslation();
	}

}
