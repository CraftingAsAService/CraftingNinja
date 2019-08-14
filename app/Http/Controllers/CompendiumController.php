<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Equipment;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;

class CompendiumController extends Controller
{

	public function index(Request $request)
	{
		$wasReferred = $request->server->get('HTTP_REFERER')
			? parse_url($request->server->get('HTTP_REFERER'))['host'] != $request->server->get('HTTP_HOST')
			: true;

		$searchTerm = $request->input('search');
		$chapterStart = $request->input('chapter');
		$filterStart = [];
		if ($request->has('author'))
			$filterStart['author'] = $request->input('author');

		$this->shareStaticGameDataWithView();

		return view('game.compendium', compact('wasReferred', 'searchTerm', 'chapterStart', 'filterStart'));
	}

	private function shareStaticGameDataWithView()
	{
		list($jobs, $max) = Cache::rememberForever(config('game.slug') . 'gameJobsAndMaxLevels', function() {
			return [
				Job::byTypeAndTier(),
				[
					'ilvl' => Item::max('ilvl'),
					'elvl' => Equipment::max('level'),
					'rlvl' => Recipe::max('level'),
				]
			];
		});

		view()->share(compact('jobs', 'max'));
	}

}
