<?php

namespace App\Models\Game\Concepts;

class JobGroup extends \App\Models\Game\Concept
{

	// There's only ever one job, but it's set up as an arry
	//  Just the way I set up the table
	public function job()
	{
		return $this->belongsTo(\App\Models\Game\Aspects\Job::class)->withTranslation();
	}

}
