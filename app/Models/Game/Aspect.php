<?php

namespace App\Models\Game;

use App\Models\Game\Entity;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
/**
 * Common aspects of a game share the same basic setup
 * 	Aspects are more akin to Game Entities
 * 	They will all have translatable datapoints: names, descriptions, etc
 */
class Aspect extends Entity implements TranslatableContract
{

	use Translatable;
	// Always load the translations in when loading a entity
	// protected $with = ['translations'];
	// ^ Decommissioned, use ->withTranslation() where needed

}
