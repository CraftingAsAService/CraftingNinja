<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Attribute;
use App\Models\Game\Aspects\Category;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Detail;
use App\Models\Game\Concepts\Equipment;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Price;
use App\Models\Translations\ItemTranslation;

class Item extends Aspect
{

	public $translationModel = ItemTranslation::class;
	public $translatedAttributes = [ 'name', 'description' ];

	/**
	 * Scopes
	 */

	static public function scopeFilter($query, $filters)
	{
		$sorting = $filters['sorting'] ?? null;
		$ordering = $filters['ordering'] ?? null;

		// Necessary for correct results
		$query->select('items.*')
			->join('item_translations as t', 't.item_id', '=', 'items.id')
			->where('locale', config('app.locale'));

		// Sort by name, or ilvl then name
		if ($sorting == 'name')
			$query->orderBy('t.name', $ordering);
		else if ($sorting == 'ilvl')
			$query->orderBy('ilvl', $ordering)->orderBy('t.name');

		// Filter by the name
		if (isset($filters['name']))
			$query->where('t.name', 'like', '%' . str_replace(' ', '%', $filters['name']) . '%');

		// Filter by the ilvl range
		$query->filterByLevelRange($filters, 'ilvl', 'ilvlMin', 'ilvlMax');

		// Other matches
		$query->filterByValues($filters, ['category_id', 'rarity']);

		// Are there any recipe filters?
		$query->filterRecipes($filters);

		// Are there any equipment filters?
		$query->filterEquipment($filters);

		return $query;
	}

	public function scopeFilterRecipes($query, $filters)
	{
		$recipeFilters = array_intersect([
			'recipes',
			'sublevel',
			'rclass',
		], array_keys($filters));

		if ( ! empty($recipeFilters))
		{
			$query->join('recipes', 'recipes.item_id', '=', 'items.id')
				->groupBy('items.id');

			if (isset($filters['sublevel']))
				$query->where('recipes.sublevel', $filters['sublevel']);

			if (isset($filters['rclass']))
				$query->whereIn('recipes.job_id', explode(',', $filters['rclass']));
		}

		return $query;
	}

	public function scopeFilterEquipment($query, $filters)
	{
		$equipmentFilters = array_intersect([
			'equipment',
			'elvlMin',
			'elvlMax',
			'sockets',
			'slot',
			'eclass',
		], array_keys($filters));

		if ( ! empty($equipmentFilters))
		{
			$query->join('equipment', 'equipment.item_id', '=', 'items.id')
				->groupBy('items.id');

			// Filter by the elvl range
			$query->filterByLevelRange($filters, 'equipment.level', 'elvlMin', 'elvlMax');

			if (isset($filters['sockets']))
				$query->where('equipment.sockets', $filters['sockets']);

			if (isset($filters['slot'])) {
				// "slot" values will be "hands"/etc. Convert them to IDs
				$query->whereIn('equipment.slot', array_keys(array_intersect(config('game.slotToEquipment'), explode(',', $filters['slot']))));
			}

			// if (isset($filters['eclass']))
			// 	$query->join('job_groups', 'job_groups.group_id', '=', 'equipment.job_group_id')
			// 		->whereIn('job_groups.job_id', explode(',', $filters['eclass']));
		}

		return $query;
	}

	// public function scopeOrderByField($query, $field, $direction = 'asc')
	// {
	// 	if ($field == 'ilvl')
	// 		$query->orderBy('ilvl', $direction);
	// 	elseif ($field == 'name')
	// 		$query->orderByName($direction);
	// }

	// public function scopeOrderByName($query, $direction = 'asc')
	// {
	// 	return $query->orderBySub(
	// 		\App\Models\Translations\ItemTranslation::select('name')
	// 			// Use both the current and fallback locales for sorting
	// 			->whereRaw('item_translations.locale in ("' . config('app.locale') . '", "' . config('app.fallback_locale') . '")')
	// 			->whereRaw('item_translations.item_id = items.id')
	// 			->orderByRaw('FIELD(item_translations.locale, "' . config('app.locale') . ',' . config('app.fallback_locale') . '")')
	// 	, $direction);
	// }

	// public function scopeWhereFilters($query, $filters = [])
	// {
	// 	$filters = collect($filters);

	// 	$query->when($filters->get('search'), function($query, $search) {
	// 		$query->whereSearch($search);
	// 	});
	// }

	// public function scopeWhereSearch($query, $string)
	// {
	// 	foreach (explode('*', $string) as $term)
	// 		$query->whereExists(function($query) use ($term) {
	// 			$query->selectRaw(1)
	// 				->from('item_translations')
	// 				->whereRaw('item_translations.item_id = items.id')
	// 				// Only name search against the current locale
	// 				->whereRaw('item_translations.locale = "' . config('app.locale') . '"')
	// 				->whereRaw('item_translations.name like "%' . $term . '%"');
	// 		});
	// }

	// public function scopeWithRelationshipCounts($query, $relationships = '')
	// {
	// 	collect(explode(',', $relationships))->each(function($relationship) use ($query) {
	// 		$query->withCount($relationship);
	// 	});
	// }

	// public function scopeSortByRecipeLevel($query, $direction = 'asc')
	// {
	// 	return $query->select('items.*')
	// 		->join('recipes as r', 'r.item_id', '=', 'items.id')
	// 		->groupBy('items.id')
	// 		->orderBy('r.level', $direction);
	// }

	// public function scopeFilterEquipmentJob($query, $jobIds)
	// {
	// 	if ( ! is_array($jobIds))
	// 		$jobIds = [ $jobIds ];

	// 	return $query->with('equipment.jobGroups', 'equipment.jobGroups.jobs')->whereHas('equipment.jobGroups.jobs', function($query) use ($jobIds) {
	// 			$query->whereIn('id', $jobIds);
	// 		});
	// }

	// public function scopeFilterRecipeJob($query, $jobIds)
	// {
	// 	if ( ! is_array($jobIds))
	// 		$jobIds = [ $jobIds ];

	// 	return $query->whereHas('recipes', function($recipeQuery) use ($jobIds) {
	// 			$recipeQuery->whereIn('job_id', $jobIds);
	// 		});
	// }

	// public function scopeFilterLevelBetween($query, $imin = null, $imax = null)
	// {
	// 	if ($imin)
	// 		$query->where('ilvl', '>=', $imin);

	// 	if ($imax)
	// 		$query->where('ilvl', '<=', $imax);

	// 	return $query;
	// }

	// public function scopeFilterRecipeLevelBetween($query, $rmin = null, $rmax = null)
	// {
	// 	if ($rmin)
	// 		$query->whereHas('recipes', function($recipeQuery) use ($rmin) {
	// 			$recipeQuery->where('level', '>=', $rmin);
	// 		});

	// 	if ($rmax)
	// 		$query->whereHas('recipes', function($recipeQuery) use ($rmax) {
	// 			$recipeQuery->where('level', '<=', $rmax);
	// 		});

	// 	return $query;
	// }

	// public function scopeFilterEquipmentLevelBetween($query, $emin = null, $emax = null)
	// {
	// 	if ($emin)
	// 		$query->whereHas('equipment', function($equipmentQuery) use ($emin) {
	// 			$equipmentQuery->where('level', '>=', $emin);
	// 		});

	// 	if ($emax)
	// 		$query->whereHas('equipment', function($equipmentQuery) use ($emax) {
	// 			$equipmentQuery->where('level', '<=', $emax);
	// 		});

	// 	return $query;
	// }

	// public function scopeFilterRecipeSubLevelIn($query, $sublevels = [])
	// {
	// 	if ( ! is_array($sublevels))
	// 		$sublevels = [ $sublevels ];

	// 	return $query->whereHas('recipes', function($recipeQuery) use ($sublevels) {
	// 			$recipeQuery->whereIn('sublevel', $sublevels);
	// 		});
	// }

	// public function scopeFilterEquipmentSocketsIn($query, $sockets = [])
	// {
	// 	if ( ! is_array($sockets))
	// 		$sockets = [ $sockets ];

	// 	return $query->whereHas('equipment', function($equipmentQuery) use ($sockets) {
	// 			$equipmentQuery->whereIn('sockets', $sockets);
	// 		});
	// }

	// public function scopeFilterCategoryHeirarchy($query, $categories = [], $excludeCategories = false)
	// {
	// 	return $query->whereHas('category', function($categoryQuery) use ($categories, $excludeCategories) {
	// 		$whereIn = 'where' . ($excludeCategories ? 'Not' : '') . 'In';
	// 		$categoryQuery->{$whereIn}('id', $categories)->{'or' . $whereIn}('category_id', $categories);
	// 	});
	// }

	// public function scopeFilterAttributes($query, $attributes = [])
	// {
	// 	return $query->select('items.*')
	// 		->join('item_attribute as ia', 'ia.item_id', '=', 'items.id')
	// 		->groupBy('items.id')
	// 		->whereIn('attribute_id', $attributes);
	// }

	/**
	 * Relationships
	 */

	public function attributes()
	{
		return $this->belongsToMany(Attribute::class)->withTranslation()->withPivot('quality', 'value');
	}

	public function category()
	{
		return $this->belongsTo(Category::class)->withTranslation();
	}

	public function equipment()
	{
		return $this->hasOne(Equipment::class);
	}

	public function recipes()
	{
		return $this->hasMany(Recipe::class)->withTranslation();
	}

	public function ingredientsOf()
	{
		return $this->belongsToMany(Recipe::class)->withTranslation()->withPivot('quantity');
	}

	public function prices()
	{
		return $this->belongsToMany(Price::class);
	}

	public function usedAsCurrency()
	{
		return $this->hasMany(Price::class);
	}

	public function npcs()
	{
		return $this->belongsToMany(Npc::class)->withTranslation()->withPivot('rate');
	}

	public function nodes()
	{
		return $this->belongsToMany(Node::class)->withTranslation();
	}

	public function rewardedFrom()
	{
		return $this->belongsToMany(Objective::class)->withTranslation()->withPivot('reward', 'quantity', 'quality', 'rate')->wherePivot('reward', true);
	}

	public function requirementOf()
	{
		return $this->belongsToMany(Objective::class)->withTranslation()->withPivot('reward', 'quantity', 'quality', 'rate')->wherePivot('reward', false);
	}

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z', 'radius');
	}

	public function listings()
	{
		return $this->morphToMany(Listing::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	/**
	 * Functions
	 */

}
