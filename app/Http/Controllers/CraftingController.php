<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CraftingController extends Controller
{

	public function index()
	{
		return view('game.crafting');
	}

}
