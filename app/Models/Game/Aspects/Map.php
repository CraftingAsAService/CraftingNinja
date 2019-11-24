<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;

class Map extends Aspect
{

	/**
	 * Mutators and Accessors
	 */

	public function getImageAttribute($identifier)
	{
		dd($this->details);
		// Icon is likely a five digit number, or less. 12345
		//  Icons are stored in a folder structure based on six digits, with only the first 3 mattering. (12345 == 012000)
		$identifier = str_pad($identifier, 6, "0", STR_PAD_LEFT);
		$folder = substr($identifier, 0, 3) . "000";
		return $folder . '/' . $identifier;
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
