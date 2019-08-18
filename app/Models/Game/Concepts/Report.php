<?php

namespace App\Models\Game\Concepts;

use App\Exceptions\NotMorphableException;
use App\Models\Game\Concept;
use Illuminate\Database\Eloquent\Relations\Relation;

class Report extends Concept
{

	public $timestamps = true;

	/**
	 * Relationships
	 */

	public function reportable()
	{
		return $this->morphTo();
	}

	/**
	 * Functions
	 */

	static public function record($id, $type, $reason)
	{
		// Confirm $type is a legitimate morph
		if ( ! isset(Relation::morphMap()[$type]))
			throw new NotMorphableException;

		// Additional protections based on type
		if ($type == 'scroll')
			Scroll::findOrFail($id);

		// Users can only report once
		return Report::updateOrCreate([
			'user_id' => auth()->user()->id,
			'reportable_id' => $id,
			'reportable_type' => $type,
		], [
			'locale' => app()->getLocale(),
			'reason' => $reason,
		]);
	}

}
