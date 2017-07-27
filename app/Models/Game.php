<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

	public $timestamps = false;

	use Translatable;

	public $translationModel = 'App\Models\Translations\GameTranslations';

	public $translatedAttributes = ['name', 'abbreviation', 'description'];

	protected $fillable = ['slug', 'version', 'name', 'abbreviation', 'description'];

	// Always load the translations in when loading a entity
	protected $with = ['translations'];

}
