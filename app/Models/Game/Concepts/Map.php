<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concept;

class Map extends Concept
{

	/**
	 * Mutators and Accessors
	 */

	public function getImageAttribute($identifier)
	{
		// a1b2/34 becomes a1b2/a1b2.34 (Assumption is that "a1b2/a1b2.34.jpg" exists)
		if ( ! $this->detail->data['image'])
			return 'none';
		list($key, $number) = explode('/', $this->detail->data['image']);
		return $key . '/' . $key . '.' . $number;
	}

	// Converting coordinates to a 2D map
	// https://github.com/xivapi/ffxiv-datamining/blob/ae773522ec3c1f21e5ea5897c3b30eef43d0460c/docs/MapCoordinates.md
	//

	/**
	 * Relationships
	 */

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

	public function zones()
	{
		return $this->belongsTo(Zone::class)->withTranslations();
	}

}
