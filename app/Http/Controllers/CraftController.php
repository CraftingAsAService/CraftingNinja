<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Sling;
use Illuminate\Http\Request;

class CraftController extends Controller
{

	public function index()
	{
		$cookieContents = Sling::parseCookie();

		// If the user had these items marked as a recipe, pre-select it as a recipe
		$recipes = $cookieContents->filter(function($row) {
			return $row['type'] == 'recipe';
		});
		$preferredRecipeIds = $recipes->pluck('id');

		// Users can add items themselves
		//  AND obviously recipes produce items
		$itemIds = $cookieContents->filter(function($row) {
			return $row['type'] == 'item';
		})->pluck('id')->merge($recipes->pluck('item_id'));

		// We have a list of items we want.
		//  Get all of these items and the ways to obtain them
		//  If the item has a recipe, prefer that recipe (paying attention to the preferredRecipes)
		//   and recursively loop through its ingredients
		$this->lineup = [
			'items'   => [],
			'recipes' => [],
			'nodes'   => [],
			'enemies' => [],
			'vendors' => [],
		];
		$lineup = $this->recursiveItemDiscovery($itemIds);

		dd($lineup);




		return view('game.craft');
	}

	private function recursiveItemDiscovery($itemIds = [])
	{

		Item::whereIn('id', $itemIds)->get();

	}

}
