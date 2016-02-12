<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Game;

class GamesController extends Controller
{

    public function index(Request $request)
    {
    	$games = Game::orderBy('slug')->get();

    	$selection = $request->get('selection');

    	return view('games.index', compact('games', 'selection'));
    }

    public function switchGame(Request $request, $slug)
    {
    	$game = Game::where('slug', $slug)->first();

    	if (isset($game->exists))
    	{
			session([
				'gid' => $game->id,
				'game-slug' => $game->slug,
			]);
    		flash()->success('Welcome to ' . strtoupper($slug));
    		if ($request->has('selection'))
    			return redirect('/' . $game->slug . '/' . $request->get('selection'));
    		return redirect('/');
    	}

    	flash()->error('Game does not exist');
    	return redirect()->back();
    }

}
