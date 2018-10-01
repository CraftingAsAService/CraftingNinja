<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\Games as GamesCollection;

use App\Models\Game;

class GameController extends Controller
{

	public function index(Request $request)
	{
		$games = Game::withTranslation()->orderByName()->paginate(10);
		return new GamesCollection($games);
	}

}
