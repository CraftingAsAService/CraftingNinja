<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Scroll;
use Illuminate\Http\Request;

class KnapsackController extends Controller
{

	public function index()
	{
		$ninjaCart = Knapsack::parseCookie();
		$scrolls = Scroll::with('job', 'votes', 'items', 'recipes', 'recipes.product', 'objectives')->authoredByUser()->get();

		return view('game.knapsack', compact('ninjaCart', 'scrolls'));
	}

}
