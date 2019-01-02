<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concept;

class Price extends Concept
{

	/**
	 * Relationships
	 */

	public function alternateCurrency()
	{
		$this->belongsTo(Item::class);
	}

	public function items()
	{
		$this->belongsToMany(Item::class);
	}

}
