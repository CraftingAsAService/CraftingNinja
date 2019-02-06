<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Recipe;

use App\Models\Game\Concepts\Equipment;

class CompendiumController extends Controller
{

	public function index(Request $request)
	{
		// TODO Cache this
		$jobs = Job::byTypeAndTier();
		$max = [
			'ilvl' => Item::max('ilvl'),
			'elvl' => Equipment::max('level'),
			'rlvl' => Recipe::max('level'),
		];

		$searchTerm = $request->input('search');

		return view('game.compendium', compact('jobs', 'max', 'searchTerm'));
	}

}
