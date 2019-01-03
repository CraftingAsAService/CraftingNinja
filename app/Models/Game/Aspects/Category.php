<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Detail;
use App\Models\Game\Translations\CategoryTranslation;

class Category extends Aspect
{
	public $translationModel = CategoryTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Relationships
	 */

	public function parent()
	{
		return $this->belongsTo(Category::class)->withTranslation();
	}

	public function items()
	{
		return $this->hasMany(Item::class)->withTranslation();
	}

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

	/**
	 * Functions
	 */
	public static function tree($allCategories = false)
	{
		if ( ! $allCategories)
			$allCategories = Category::orderBy('id')->get();

		$parents = $allCategories->filter(function($category) {
			return $category->category_id == 0;
		})->pluck('name', 'id');

		$tree = [];
		foreach ($parents as $pid => $pname)
			$tree[$pid] = [
				'id' => $pid,
				'name' => $pname,
				'display' => true,
				'children' => $allCategories->filter(function($category) use ($pid) {
						return $category->category_id == $pid;
					})->map(function($category) {
						return [
							'id' => $category->id,
							'name' => $category->name,
							'display' => true
						];
					})->toArray()
			];

		return $tree;
	}

}
