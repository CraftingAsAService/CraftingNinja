<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Concept;

class Detail extends Concept
{

	protected $casts = [
		'data' => 'array',
	];

	/**
	 * Relationships
	 */

	public function detailable()
	{
		return $this->morphTo()->withTranslation();
	}

}
