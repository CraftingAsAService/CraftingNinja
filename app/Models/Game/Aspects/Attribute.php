<?php

namespace App\Models\Game\Aspects;

class Attribute extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\AttributeTranslation::class;
	public $translatedAttributes = ['name'];

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
		$this->belongToMany(Item::class, 'item_attribute')->withPivot('quality', 'value');
	}

}
