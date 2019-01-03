<?php

namespace App\Models\Game\Concepts;

use App\Models\Game\Concept;

class Detail extends Concept
{

	/**
	 * Relationships
	 */

	public function detailable()
	{
		return $this->morphTo()->withTranslation();
	}

}
