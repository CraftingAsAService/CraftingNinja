<?php

return [

	// Splitting into Entity and Pivot tables
	//  the distinction may serve a purpose in the future

	'entityTables' => [
		'attributes',
		'categories',
		'items',
		'jobs',
		'maps',
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
		'item_npc', // FFXIV: CSV will have duplicates due to the way data is collected
		'item_objective',
		'item_price', // FFXIV: CSV will have duplicates due to the way data is collected
		'item_recipe', // FFXIV: CSV will have duplicates due to the way data is collected [Company Crafts]
		'item_shop', // FFXIV: CSV will have duplicates due to the way data is collected
		'item_translations',
		'job_niche',
		'job_translations',
		// 'node_translations', // Empty for FFXIV, relies on Zone Name
		'npc_shop',
		'npc_translations', // FFXIV: CSV will have duplicates due to the way data is collected
		'objective_translations',
		'prices',
		// 'recipe_translations', // Empty for FFXIV, relies on Item Name
		'shop_translations',
		'zone_translations',
	],

];
