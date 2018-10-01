<?php

namespace App\Models\Game;

use Dimsav\Translatable\Translatable;

/**
 * Common aspects of a game share the same basic setup
 * 	Aspects are more akin to Game Entities
 */
class Aspect extends Entity
{

	use Translatable;
	public $translatedAttributes = ['name', 'description'];
	// Always load the translations in when loading a entity
	// protected $with = ['translations'];

}
