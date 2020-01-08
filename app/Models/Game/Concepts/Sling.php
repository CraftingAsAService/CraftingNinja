<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;

class Sling {

	protected $scroll;

	static public function parseCookie()
	{
		$ninjaCartCookie = getCookieValue('NinjaCart');
		$ninjaCart = $ninjaCartCookie ? json_decode($ninjaCartCookie) : [];

		foreach ($ninjaCart as &$entry)
			$entry = Sling::convert($entry);
		unset($entry);

		// array_diff won't work here; multidimensional array
		$ninjaCart = array_filter($ninjaCart, function($value) {
			return $value !== null;
		});

		return collect($ninjaCart);
	}

	static public function convert($entry)
	{
		$results = null;

		if ($entry->t == 'item')
			$results = Item::withTranslation()->find($entry->i);
		elseif ($entry->t == 'recipe')
			$results = Recipe::withTranslation()->with('product', 'job')->find($entry->i);

		if (is_null($results) || ! $results->exists)
			return null;

		$results = $results->toArray();

		if ($entry->t == 'recipe')
		{
			$results['icon'] =& $results['product']['icon'];
			$results['name'] =& $results['product']['name'];
		}

		$results['type'] = $entry->t;
		$results['quantity'] = $entry->q;

		return $results;
	}

	static public function unsetCookie()
	{
		if ( ! app()->environment('testing'))
			setcookie('NinjaCart', '', time() - 3600);
	}

	public function truncate()
	{
		foreach (Scroll::$polymorphicRelationships as $relation)
			foreach ($this->scroll->$relation as $entity)
				$this->remove($entity->id, $entity->pivot->jotting_type);
	}

}
