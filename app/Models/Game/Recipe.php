<?php

namespace App\Models\Game;

use App\Models\Game\Job;

class Recipe extends BasicGameEntity
{
	public $translationModel = 'App\Models\Translations\RecipeTranslations';

	protected $fillable = ['job_id', 'level', 'yield', 'quality', 'chance', 'name', 'description'];

	/**
	 * newQuery - Append to any new Recipe call
	 *  All Recipe calls are in the context of its item's Game ID
	 * @return object	$query	Eloquent Query
	 */
	public function newQuery()
	{
		$query = parent::newQuery();

		// Every Recipe call should include a ->with('item') automagically
		// That item should also belong to the configured game
		$query->with('item')->whereHas('item', function($query) {
			return $query->whereGameId(config('game')->id);
		});

		return $query;
	}

	/**
	 * Scopes
	 */

	static public function scopeSortByItemValue($query, $sort_by, $sort_direction = 'asc')
	{
		if ($sort_by == 'name')
			$query = $query->sortByItemName($sort_direction);
		elseif ($sort_by == 'ilvl')
			$query = $query->sortByItemLevel($sort_direction);

		return $query;
	}

	public function scopeSortByItemName($query, $direction = 'asc')
	{
		return $query->select('recipes.*')
			->join('items', 'recipes.item_id', '=', 'items.id')
			->join('item_translations as t', 't.item_id', '=', 'items.id')
			->where('locale', config('app.locale'))
			->groupBy('items.id')
			->orderBy('t.name', $direction);
	}

	public function scopeSortByItemLevel($query, $direction = 'asc')
	{
		return $query->select('recipes.*')
			->join('items', 'recipes.item_id', '=', 'items.id')
			->groupBy('items.id')
			->orderBy('items.ilvl', $direction);
	}

	public function scopeFilterByItemName($query, $filter_value)
	{
		return $query->select('recipes.*')
			->join('items', 'recipes.item_id', '=', 'items.id')
			->join('item_translations as t', 't.item_id', '=', 'items.id')
			->where('locale', config('app.locale'))
			->groupBy('items.id')
			->where('t.name', 'like', '%' . str_replace(' ', '%', $filter_value) . '%');
	}

	/**
	 * Accessors & Mutators
	 */

	/**
	 * Relationships
	 */

	public function item()
	{
		return $this->belongsTo('App\Models\Game\Item');//->whereGameId(config('game')->id);
	}

	// public function job()
	// {
	// 	return $this->belongsTo('App\Models\Game\Job');
	// }

	// public function items()
	// {
	// 	return $this->belongsToMany('App\Models\Game\Item', 'recipe_rewards')->withPivot('yield', 'quality', 'chance');
	// }

	// public function requirements()
	// {
	// 	return $this->belongsToMany('App\Models\Game\Item', 'recipe_requirements')->withPivot('amount', 'quality', 'used');
	// }

	// public function stats()
	// {
	// 	return $this->belongsToMany('App\Models\Game\Stat')->withPivot('amount');
	// }


	/**
	 * Functions
	 */

}
