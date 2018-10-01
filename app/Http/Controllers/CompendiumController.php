<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Recipe;

use App\Models\Game\Concepts\Equipment;

class CompendiumController extends Controller
{

	public function index()
	{
		// TODO Cache this
		$jobs = Job::byTypeAndTier();
		$ilvlMax = Item::max('ilvl');
		$elvlMax = Equipment::max('level');
		$rlvlMax = Recipe::max('level');

		return view('game.compendium', compact('jobs', 'ilvlMax', 'elvlMax', 'rlvlMax'));
	}

}
