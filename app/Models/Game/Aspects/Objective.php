<?php

namespace App\Models\Game\Aspects;

use App\Models\Game\Aspect;
use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Npc;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Detail;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Niche;
use App\Models\Game\Translations\ObjectiveTranslation;

class Objective extends Aspect
{

	public $translationModel = ObjectiveTranslation::class;
	public $translatedAttributes = [ 'name', 'description' ];

	/**
	 * Relationships
	 */

	public function detail()
	{
		return $this->morphOne(Detail::class, 'detailable');
	}

	public function zones()
	{
		return $this->morphToMany(Zone::class, 'coordinate')->withTranslation()->withPivot('x', 'y', 'z', 'radius');
	}

	public function listings()
	{
		return $this->morphToMany(Listing::class, 'jotting')->withTranslation()->withPivot('quantity');
	}

	public function rewards()
	{
		return $this->belongsToMany(Item::class)->withTranslation()->withPivot('reward', 'quantity', 'quality', 'rate')->wherePivot('reward', true);
	}

	public function requirements()
	{
		return $this->belongsToMany(Item::class)->withTranslation()->withPivot('reward', 'quantity', 'quality', 'rate')->wherePivot('reward', false);
	}

	public function issuer()
	{
		return $this->belongsTo(Npc::class, 'issuer_id')->withTranslation();
	}

	public function target()
	{
		return $this->belongsTo(Npc::class, 'target_id')->withTranslation();
	}

	public function niche()
	{
		return $this->belongsTo(Niche::class);
	}

}
