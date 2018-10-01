<?php

namespace App\Models;

use Watson\Rememberable\Rememberable;
use Dimsav\Translatable\Translatable;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

	protected $connection = 'caas';

	public $timestamps = false;

	use Translatable;
	public $translatedAttributes = ['name', 'abbreviation', 'description'];
	// Always load the translations in when loading a entity
	// protected $with = ['translations'];

	protected $guarded = [];

	use Rememberable;

	public function scopeOrderByName($query, $direction = 'asc')
	{
		return $query->orderBySub(
			GameTranslation::select('name')
				// Use both the current and fallback locales for sorting
				->whereRaw('game_translations.locale in ("' . config('app.locale') . '", "' . config('app.fallback_locale') . '")')
				->whereRaw('game_translations.game_id = games.id')
				->orderByRaw('FIELD(game_translations.locale, "' . config('app.locale') . ',' . config('app.fallback_locale') . '")')
		, $direction);
	}

}
