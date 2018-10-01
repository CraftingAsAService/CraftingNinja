<?php

namespace App\Models\Game\Aspects;

use Dimsav\Translatable\Translatable;

class Category extends \App\Models\Game\Aspect
{
	public $translationModel = \App\Models\Game\Translations\CategoryTranslation::class;
	public $translatedAttributes = ['name'];

	/**
	 * Relationships
	 */

	public function parent()
	{
		return $this->belongsTo(Category::class, 'category_id')->withTranslation();
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
