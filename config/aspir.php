<?php

return [

	// Splitting into Entity and Pivot tables
	//  the distinction may serve a purpose in the future

	'entityTables' => [
		'attributes',
		'categories',
		'items',
		'jobs',
		'niches',
		'nodes',
		'npcs',
		'objectives',
		'recipes',
		'shops',
		'zones',
	],

	'pivotTables' => [
		'attribute_item',
		'attribute_translations',
		'category_translations',
		'coordinates',
		'details',
		'equipment',
		'item_node',
		'item_npc',
		'item_objective',
		'item_price',
		'item_recipe',
		'item_shop',
		'item_translations',
		'job_niche',
		'job_translations',
		// 'node_translations', // Empty for FFXIV, relies on Zone Name
		'npc_shop',
		'npc_translations',
		'objective_translations',
		'prices',
		// 'recipe_translations', // Empty for FFXIV, relies on Item Name
		'shop_translations',
		'zone_translations',
	],

];
