<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Game;

class CoreController extends Controller
{

	public function home()
	{
		if (config('gameSlug'))
			return $this->gameHome();

		// Get a list of all games
		$allGames = Game::all();

		return view('core.home', compact('allGames'));
	}

	public function gameHome()
	{
		// Game centric home
		return view('game.home');
	}
}
