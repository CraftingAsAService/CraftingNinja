<?php

namespace App\Models\Game\Aspects;

class Zone extends \App\Models\Game\Aspect
{

	public $translationModel = \App\Models\Game\Translations\ZoneTranslation::class;

	/**
	 * Relationships
	 */

	public function parent()
	{
		return $this->belongsTo(Zone::class);
	}

}
