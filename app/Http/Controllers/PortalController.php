<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Games as GamesCollection;

use App\Models\Game;

class PortalController extends Controller
{

	public function index()
	{
		$games = Game::withTranslation()->orderByName()->get();
		return view('portal.index', compact('games'));
	}

}
