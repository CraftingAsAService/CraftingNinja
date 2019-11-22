<?php

return [

	'maxDifficulty' => 4,
	'maxSockets' => 5,

	'map' => [
		// All given map coordinates exist on a scale from zero to these numbers
		'coordinates' => [
			'width' => 44, // == 100%
			'height' => 44,
		]
	],

	'equipmentLayout' => [
		// Label -> Key
		'Main Hand' => 'primary',
		'Off Hand' => 'secondary',
		'Head' => 'head',
		'Body' => 'body',
		'Hands' => 'hands',
		'Waist' => 'waist',
		'Legs' => 'legs',
		'Feet' => 'feet',
		'Ears' => 'ears',
		'Neck' => 'neck',
		'Wrists' => 'wrists',
		'Left Ring' => 'ring',
		'Right Ring' => 'ring',
		// Ignoring: Soul Crystal - Not worth including
	],

	// Slot defines a multitude of options, like cannot equip/etc
	'slotToEquipment' => [
		// IDs come straight from game data
		// 		// If it's a string, no restrictions
		1	=> 'primary',
		2	=> 'secondary',
		3	=> 'head',
		4	=> 'body',
		5	=> 'hands',
		6	=> 'waist',
		7	=> 'legs',
		8	=> 'feet',
		9	=> 'ears',
		10	=> 'neck',
		11	=> 'wrists',
		12	=> 'ring',
		// "Complicated" Slots, any of these also have restrictions, listed in `slotRestrictions`
		13	=> 'primary',
		// 	14 is Undefined
		15	=> 'body',
		16	=> 'body',
		// 	17 is the Soul Crystal, which is being ignored/unsupported
		18	=> 'legs',
		19	=> 'body',
		// 	20 is Undefined
		21	=> 'body',
	],

	'slotRestrictions' => [
		13	=> [ 'secondary' ], // e.g. Cannot equip anything in the Primary slot
		15	=> [ 'head' ],
		16	=> [ 'hands', 'legs', 'feet' ],
		18	=> [ 'feet' ],
		19	=> [ 'head', 'hands', 'legs', 'feet' ],
		21	=> [ 'legs', 'feet' ],
	],

	'rarity' => [
		// 0	=> 'None',
		1	=> 'Common',
		2	=> 'Uncommon',
		3	=> 'Rare',
		4	=> 'Relic',
		7	=> 'Aetherial',
	],

	'jobTiers' => [
		0 => 'Classes',
		1 => 'Jobs',
		2 => 'Adv. Jobs',
	],

	'ignoreJobs' => [
		255, // Exclude the ADV/Adventurer
	],

	'nodeTypes' => [
		0 => [
			'name' => 'Mineral Deposit',
			'icon' => 'mining',
		],
		1 => [
			'name' => 'Rocky Outcropping',
			'icon' => 'quarrying',
		],
		2 => [
			'name' => 'Mature Tree',
			'icon' => 'logging',
		],
		3 => [
			'name' => 'Lush Vegetation',
			'icon' => 'harvesting',
		],
		4 => [
			'name' => 'Spearfishing', // Spearfishing is a gathering type, not a fishing type
			'icon' => 'spearfishing',
		],
		10 => [
			'name' => 'Ocean Fishing',
			'icon' => 'fishing',
		],
		11 => [
			'name' => 'Freshwater Fishing',
			'icon' => 'fishing',
		],
		12 => [
			'name' => 'Dunefishing',
			'icon' => 'fishing',
		],
		13 => [
			'name' => 'Skyfishing',
			'icon' => 'fishing',
		],
		14 => [
			'name' => 'Cloudfishing',
			'icon' => 'fishing',
		],
		15 => [
			'name' => 'Hellfishing',
			'icon' => 'fishing',
		],
		16 => [
			'name' => 'Aetherfishing',
			'icon' => 'fishing',
		],
		17 => [
			'name' => 'Saltfishing',
			'icon' => 'fishing',
		],
	],

	'objectiveTypes' => [
		0 => 'achievement',
		1 => 'fate',
		2 => 'leve',
		3 => 'quest',
		4 => 'venture',
	],

	'xp' => [
		100,
		300,
		600,
		1100,
		1700,
		2300,
		4200,
		6000,
		7350,
		9930,
		11800,
		15600,
		19600,
		23700,
		26400,
		30500,
		35400,
		40500,
		45700,
		51000,
		56600,
		63900,
		71400,
		79100,
		87100,
		95200,
		109800,
		124800,
		140200,
		155900,
		162500,
		175900,
		189600,
		203500,
		217900,
		232320,
		249900,
		267800,
		286200,
		304900,
		324000,
		340200,
		356800,
		373700,
		390800,
		408200,
		437600,
		467500,
		498000,
		529000,
		864000,
		1058400,
		1267200,
		1555200,
		1872000,
		2217600,
		2592000,
		2995200,
		3427200,
		3888000,
		4470000,
		4873000,
		5316000,
		5809000,
		6364000,
		6995000,
		7722000,
		8575000,
		9593000,
		10826000
	],

	// Ignore these items if they're a part of a recipe
	//  Useful for crystals and other BS ingredients
	'reagentsToIgnore' => [
		2, // Fire Shard
		3, // Ice Shard
		4, // Wind Shard
		5, // Earth Shard
		6, // Lightning Shard
		7, // Water Shard
		8, // Fire Crystal
		9, // Ice Crystal
		10, // Wind Crystal
		11, // Earth Crystal
		12, // Lightning Crystal
		13, // Water Crystal
		14, // Fire Cluster
		15, // Ice Cluster
		16, // Wind Cluster
		17, // Earth Cluster
		18, // Lightning Cluster
		19, // Water Cluster
	],

];
