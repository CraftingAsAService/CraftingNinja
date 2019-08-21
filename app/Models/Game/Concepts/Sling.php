<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Scroll;

class Sling {

	protected $scroll;

	static public function parseCookie()
	{
		$ninjaCart = isset($_COOKIE['NinjaCart']) && $_COOKIE['NinjaCart']
			? json_decode($_COOKIE['NinjaCart'])
			: [];

		foreach ($ninjaCart as &$entry)
		{
			$original = $entry;

			if ($entry->t == 'item')
			{
				$item = Item::with('translations')->find($entry->i);
				if ($item->exists)
					$entry = $item->toArray();
				else
					continue;
			}

			$entry['type'] = $original->t;
			$entry['quantity'] = $original->q;
		}

		return collect($ninjaCart);
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
