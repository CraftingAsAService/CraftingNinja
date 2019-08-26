<?php

// Filters array used to generate both filtration anchors and filtration widgets
$filters = [
	'name' => [
		'key'	   => 'name',
		'for'      => ['scrolls', 'item', 'recipe', 'equipment', 'quest', 'mob'],
		'type'     => 'string',
		'icon'     => 'fa-search',
		'title'    => 'Search',
		'expanded' => true,
	],
	'ilvl' => [
		'key'	   => 'ilvl',
		'for'      => ['item', 'recipe', 'equipment'],
		'type'     => 'range',
		'icon'     => 'fa-info',
		'title'    => 'Item Level',
		'expanded' => true,
	],
	'rclass' => [
		'key'	   => 'rclass',
		'for'      => ['recipe'],
		'type'     => 'multiple',
		'icon'     => 'fa-chess-bishop',
		'title'    => 'Recipe Class',
		'expanded' => true,
	],
	'rlvl' => [
		'key'	 => 'rlvl',
		'for'    => ['recipe'],
		'type'   => 'range',
		'icon'   => 'fa-award',
		'title'  => 'Recipe Level',
	],
	'rdifficulty' => [
		'key'	 => 'sublevel',
		'for'    => ['recipe'],
		'type'   => 'multiple',
		'icon'   => 'fa-star',
		'title'  => 'Recipe Difficulty',
	],
	'elvl' => [
		'key'	 => 'elvl',
		'for'    => ['equipment'],
		'type'   => 'range',
		'icon'   => 'fa-medal',
		'title'  => 'Equipment Level',
	],
	'slvl' => [
		'key'	 => 'slvl',
		'for'    => ['scrolls'],
		'type'   => 'range',
		'icon'   => 'fa-medal',
		'title'  => 'Level',
	],
	'eclass' => [
		'key'	 => 'eclass',
		'for'    => ['equipment'],
		'type'   => 'multiple',
		'icon'   => 'fa-chess-rook',
		'title'  => 'Equipment Class',
	],
	'scrafting' => [
		'key'	 => 'sclass',
		'for'    => ['scrolls'],
		'type'   => 'multiple',
		'icon'   => 'fa-tools',
		'title'  => 'Crafting Class',
	],
	'sgathering' => [
		'key'	 => 'sclass',
		'for'    => ['scrolls'],
		'type'   => 'multiple',
		'icon'   => 'fa-feather-alt',
		'title'  => 'Gathering Class',
	],
	'sbattle' => [
		'key'	 => 'sclass',
		'for'    => ['scrolls'],
		'type'   => 'multiple',
		'icon'   => 'fa-chess-rook',
		'title'  => 'Battle Class',
	],
	'slot' => [
		'key'	 => 'slot',
		'for'    => ['equipment'],
		'type'   => 'multiple',
		'icon'   => 'fa-hand-paper',
		'title'  => 'Equipment Slot',
	],
	'sockets' => [
		'key'	 => 'sockets',
		'for'    => ['equipment'],
		'type'   => 'multiple',
		'icon'   => 'fa-gem',
		'title'  => 'Materia Sockets',
	],
	'rarity' => [
		'key'	 => 'rarity',
		'for'    => ['item', 'recipe', 'equipment'],
		'type'   => 'multiple',
		'icon'   => 'fa-registered',
		'title'  => 'Rarity',
	],
	'sauthor' => [
		'key'	 => 'sauthor',
		'for'    => ['scrolls'],
		'type'   => 'multiple',
		'icon'   => 'fa-user-ninja',
		'title'  => 'Author',
	],
];

return [
	'all' => $filters,

	'scrolls' => array_intersect_key($filters, array_flip(['name', 'slvl', 'scrafting', 'sgathering', 'sbattle', 'sauthor', ])),

	'recipe' => array_intersect_key($filters, array_flip(['name', 'ilvl', 'rclass', 'rlvl', 'rdifficulty', 'rarity', ])),

	'item' => array_intersect_key($filters, array_flip(['name', 'ilvl', 'rarity', ])),

	'equipment' => array_intersect_key($filters, array_flip(['name', 'ilvl', 'rarity', 'elvl', 'eclass', 'slot', 'sockets', ])),

	'sorting' => [
		'name:asc' => [
			'key'   => 'name:asc',
			'icon'  => 'fa-sort-alpha-down',
			'title' => 'Name: A-Z',
		],
		'name:desc' => [
			'key'   => 'name:desc',
			'icon'  => 'fa-sort-alpha-up',
			'title' => 'Name: Z-A',
		],
		'ilvl:asc' => [
			'key'   => 'ilvl:asc',
			'icon'  => 'fa-sort-numeric-down',
			'title' => 'iLv: Low to High',
		],
		'ilvl:desc' => [
			'key'   => 'ilvl:desc',
			'icon'  => 'fa-sort-numeric-up',
			'title' => 'iLv: High to Low',
		],
	],

	'perPage' => [
		'15' => [
			'key'   => '15',
			'icon'  => 'fa-battery-quarter',
			'title' => 'Show 15 per page',
		],
		'30' => [
			'key'   => '30',
			'icon'  => 'fa-battery-half',
			'title' => 'Show 30 per page',
		],
		'45' => [
			'key'   => '45',
			'icon'  => 'fa-battery-three-quarters',
			'title' => 'Show 45 per page',
		],
	],

];
