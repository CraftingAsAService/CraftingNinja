<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concept;
use App\Models\Game\Concepts\Niche;

class Equipment extends Concept
{

	protected $table = 'equipment'; // Otherwise laravel will assume `equipments`

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
			$query->select('equipment.*')
				->join('items', 'equipment.item_id', '=', 'items.id')
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
		$query->filterByValues($filters, ['sockets', 'slot']);

		// Filter by Equipment pieces
		if (isset($filters['eclass']))
			$query->filterByJobs(explode(',', $filters['eclass']));

		return $query;
	}

	// public function scopeFilterByJobs($query, array $jobIds)
	// {
	// 	return $query->join('job_groups as jg', 'jg.group_id', '=', 'equipment.job_group_id')
	// 				->whereIn('jg.job_id', $jobIds);
	// }

	/**
	 * Relationships
	 */

	public function item()
	{
		return $this->belongsTo(Item::class)->withTranslation();
	}

	public function niche()
	{
		return $this->belongsTo(Niche::class);
	}

}
