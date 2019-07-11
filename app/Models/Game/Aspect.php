<?php

namespace App\Models\Game;

use App\Models\Game\Entity;
use Astrotomic\Translatable\Translatable;

/**
 * Common aspects of a game share the same basic setup
 * 	Aspects are more akin to Game Entities
 * 	They will all have translatable datapoints: names, descriptions, etc
 */
class Aspect extends Entity
{

	use Translatable;
	// Always load the translations in when loading a entity
	// protected $with = ['translations'];
	// ^ Decommissioned, use ->withTranslation() where needed

}
