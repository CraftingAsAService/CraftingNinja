<?php

namespace App\Models\Game;

class Item extends BasicGameEntity
{

	public $translationModel = 'App\Models\Translations\ItemTranslations';

	protected $fillable = ['has_quality', 'level', 'ilvl', 'is_equipment', 'icon', 'name', 'description'];

	/**
	 * Scopes
	 */

	public function scopeSortByName($query, $direction = 'asc')
	{
		return $query->select('items.*')
			->join('item_translations as t', 't.item_id', '=', 'items.id')
			->where('locale', config('app.locale'))
			->groupBy('items.id')
			->orderBy('t.name', $direction)
			->with('translations');
	}

	public function scopeFilterByRecipeJob($query, $job)
	{
		return $query->select('items.*')
			->join('recipes as r', 'r.item_id', '=', 'items.id')
			->where('r.job_id', $job->id)
			->groupBy('items.id');
	}

	public function scopeSortByRecipeLevel($query, $direction = 'asc')
	{
		return $query->select('items.*')
			->join('recipes as r', 'r.item_id', '=', 'items.id')
			->groupBy('items.id')
			->orderBy('r.level', $direction);
	}

	/**
	 * Relationships
	 */

	public function recipes()
	{
		return $this->hasMany('App\Models\Game\Recipe');
	}

	/**
	 * Functions
	 */
	// static public function sortAndFilter($sort_by, $sort_direction, $filter_by, $filter_value)
	// {


	// }

}