<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Sling;
use App\Models\Game\Concepts\Scroll;
use Illuminate\Http\Request;

class SlingController extends Controller
{

	public function index()
	{
		$ninjaCart = Sling::parseCookie();
		$scrolls = Scroll::with('job', 'votes', 'items', 'recipes', 'recipes.product', 'objectives')->authoredByUser()->get();

		return view('game.sling', compact('ninjaCart', 'scrolls'));
	}

}
