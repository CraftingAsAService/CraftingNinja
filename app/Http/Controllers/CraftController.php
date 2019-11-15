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
		$this->recursiveItemDiscovery($itemIds);

		dd($this->lineup);




		return view('game.craft');
	}

	private function recursiveItemDiscovery($itemIds = [])
	{
		// !! If I build a recursive MySQL 8.0 query, I can get all the recipe and item ids I need, then query those after in one big batch!

		// TODO TODO ^ THIS THIS THIS
		//
		//
		//
		//

		// $items = Item::withTranslation()
		// 	->with(
		// 		'recipes'/*,
		// 			'ingredientsOf.ingredients'/*,
		// 		'npcs',
		// 			'npcs.zones',
		// 		'nodes',
		// 			'nodes.zones',
		// 		'rewardedFrom',
		// 			'rewardedFrom.zones',
		// 		'zones'*/
		// 	)
		// 	->whereIn('id', $itemIds->diff($this->lineup['items']))
		// 	->get();

		// // Loop through
		// //  Add Recipes to its list
		// //  Recursively go through Item Discovery on the Ingredients
		// //  Add NPCs to their list (enemies or vendors)
		// //  Add Nodes to their list
		// //  Add Rewards to their list (quests)
		// //  Add Zones to their list (treasure)

		// foreach ($items as $item)
		// {
		// 	\Log::info('Adding item ' . $item->id . ' ' . $item->name);
		// 	if ( ! isset($this->lineup['items'][$item->id]))
		// 	{
		// 		$this->lineup['items'][$item->id] = $item;

		// 		// Loop through this item's recipes
		// 		foreach ($item->recipes as $recipe)
		// 		{
		// 			\Log::info('Adding Recipe ' . $recipe->id);
		// 			if ( ! isset($this->lineup['recipes'][$recipe->id]))
		// 			{
		// 				$this->lineup['recipe'][$recipe->id] = $recipe;
		// 				// dd($recipe->product->name, $recipe->ingredients->pluck('name'));
		// 				\Log::info('Looping through recipe items');
		// 				$this->recursiveItemDiscovery($recipe->ingredients->pluck('id'));
		// 				// dd($item->id, $item->name, $recipe->item_id, $recipe->ingredients->pluck('name')->toArray());
		// 			}
		// 		}
		// 	}

		// }
	}


}
