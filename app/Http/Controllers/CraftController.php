<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
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
			'items'    => [],
			'recipes'  => [],
			'nodes'    => [],
			'enemies'  => [],
			'quests'   => [],
			'vendors'  => [],
			'treasure' => [],
		];
		$lineup = $this->recursiveItemDiscovery($itemIds);

		dd($lineup);




		return view('game.craft');
	}

	private function recursiveItemDiscovery($itemIds = [])
	{
		$items = Item::withTranslation()
			->with(
				'recipes',
					'recipes.ingredients'/*,
				'npcs',
					'npcs.zones',
				'nodes',
					'nodes.zones',
				'rewardedFrom',
					'rewardedFrom.zones',
				'zones'*/
			)
			->whereIn('id', $itemIds)
			->get();

		// Loop through
		//  Add Recipes to its list
		//  Recursively go through Item Discovery on the Ingredients
		//  Add NPCs to their list (enemies or vendors)
		//  Add Nodes to their list
		//  Add Rewards to their list (quests)
		//  Add Zones to their list (treasure)

		foreach ($items as $item)
		{
			if ( ! isset($this->lineup['items'][$item->id]))
				$this->lineup['items'][$item->id] = $item;

			$itemsToDiscover = [];
			foreach ($item->recipes as $recipe)
			{
				dd($recipe);
			}

		}
		dd($items->first());



	}


}
