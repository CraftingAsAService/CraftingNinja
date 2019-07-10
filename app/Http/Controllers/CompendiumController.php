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
		$wasReferred = $request->server->get('HTTP_REFERER')
			? parse_url($request->server->get('HTTP_REFERER'))['host'] != $request->server->get('HTTP_HOST')
			: true;

		$searchTerm = $request->input('search');

		// TODO Cache this
		$jobs = Job::byTypeAndTier();
		$max = [
			'ilvl' => Item::max('ilvl'),
			'elvl' => Equipment::max('level'),
			'rlvl' => Recipe::max('level'),
		];

		return view('game.compendium', compact('wasReferred', 'jobs', 'max', 'searchTerm'));
	}

}
