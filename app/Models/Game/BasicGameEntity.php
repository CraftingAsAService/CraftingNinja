<?php

namespace App\Models\Game;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class BasicGameEntity extends Model
{

	protected $connection = config('gameSlug');

	public $timestamps = false;

	use Translatable;

	// public $translationModel = 'App\Models\Translations\EquipmentSlotTranslations';

	public $translatedAttributes = ['name', 'description'];

	protected $fillable = ['name', 'description'];

	// Always load the translations in when loading a entity
	protected $with = ['translations'];

	// public function __construct() {
	// 	parent::__construct();
	// 	$this->$connection = config('gameSlug');
	// }

	/**
	 * Scopes
	 */
	public function scopeSort($query, $field, $asc = true)
	{
		return $query->orderBy($field, $asc ? 'asc' : 'desc');
	}

	/**
	 * Polymorphic Relationships
	 */

	// public function origins()
	// {
	// 	return $this->morphMany('App\Models\Origin', 'origins');
	// }

	// public function revisions()
	// {
	// 	return $this->morphMany('App\Models\Revisions', 'revisions');
	// }

	// public function tags()
	// {
	// 	return $this->morphToMany('App\Models\Tag', 'taggables');
	// }

	/**
	 * Relationships
	 */



}
