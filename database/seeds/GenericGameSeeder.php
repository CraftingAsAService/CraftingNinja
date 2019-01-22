<?php

use Illuminate\Database\Seeder;

abstract class GenericDataSeeder extends Seeder
{

	protected $seedData = [
			// Table Names, camel_case() for relevant filenames in storage
			'category_translations',
			'categories',
			'item_price',
			'prices',
			'item_translations',
			'items',
			'job_niches',
			'niches',
			'job_translations',
			'jobs',
			'item_recipes',
			'recipe_translations',
			'recipes',
			'attribute_items',
			'attribute_translations',
			'attributes',
			'equipment',
			'item_npcs',
			'npc_translations',
			'npcs',
			'npc_shops',
			'shop_translations',
			'shops',
			'zone_translations',
			'zones',
			'coordinates',
			'item_objectives',
			'objective_translations',
			'objectives',
			'details',
			'item_nodes',
			'node_translations',
			'nodes',
		];

	public function __construct()
	{
		parent::__construct();

		Model::unguard();

		// Don't bother logging queries
		\DB::connection()->disableQueryLog();

		$slug = preg_replace('/seeder$/', '', strtolower((new \ReflectionClass($this))->getShortName()));

		$this->dataLocation = env('DATA_REPOSITORY') . '/' . $slug . '/parsed/';
	}

}
