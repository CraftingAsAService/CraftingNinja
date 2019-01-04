<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Item;
use App\Models\Translations\AttributeTranslation;

class Attribute extends Aspect
{

	public $translationModel = AttributeTranslation::class;
	public $translatedAttributes = [ 'name' ];

	/**
	 * Scopes
	 */

	public function scopeSortByName($query, $direction = 'asc')
	{
		return $query->select('attributes.*')
			->join('attribute_translations as t', 't.attribute_id', '=', 'attributes.id')
			->where('locale', config('app.locale'))
			->groupBy('attributes.id')
			->orderBy('t.name', $direction)
			->with('translations');
	}

	/**
	 * Relationships
	 */

	public function items()
	{
		$this->belongToMany(Item::class)->withTranslation()->withPivot('quality', 'value');
	}

}
