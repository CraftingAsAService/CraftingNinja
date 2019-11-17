<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Recipe;
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
			'recipes'  => [],
			'items'    => [],
			'nodes'    => [],
			'enemies'  => [],
			'quests'   => [],
			'vendors'  => [],
			'treasure' => [],
		];
		$this->recursiveItemDiscovery($itemIds);

		dd(implode(',', array_keys($this->lineup['items'])), implode(',', array_keys($this->lineup['recipes'])));




		return view('game.craft');
	}

	private function recursiveItemDiscovery($itemIds = [])
	{
		// If it's not a digit, or a comma, take it out.
		//  Custom XSS/etc prevention
		$itemIds = preg_replace('/[^\d,]/', '', $itemIds->implode(','));

		$results = collect(\DB::select(
			'WITH RECURSIVE cte AS (' .
				'SELECT rr.recipe_id, rr.item_id ' .
				'FROM recipes r ' .
				'JOIN item_recipe rr ON rr.recipe_id = r.id ' .
				'WHERE r.item_id IN ( ' . $itemIds . ') ' .
				'UNION ALL ' .
				'SELECT rr.recipe_id, rr.item_id ' .
				'FROM recipes r ' .
				'JOIN item_recipe rr ON rr.recipe_id = r.id ' .
				'JOIN cte ON cte.item_id = r.item_id ' .
			') SELECT recipe_id, item_id FROM cte;'
		));

		$recipeIds = $results->pluck('recipe_id')->unique();
		$itemIds = $results->pluck('item_id')->unique();

		$this->lineup['recipes'] = Recipe::whereIn('id', $recipeIds)
			->get()
			->keyBy('id');

		$this->lineup['items'] = Item::with(
				'nodes', // Gathering drops
					'nodes.zones',
				'mobs', // Mob drops
					'mobs.zones',
				'rewardedFrom', // Objective drops
					'rewardedFrom.zones',
				'shops',
					'shops.zones',
				'zones', // Treasure drops
				'prices',
			)
			->whereIn('id', $itemIds)
			->get()
			->keyBy('id');

		foreach ($this->lineup['items'] as $item)
		{
			dd($item);
		}

		dd($this->lineup);

		dd($recipeIds, $itemIds);
		// dd($itemIds->join(','));
		// !! If I build a recursive MySQL 8.0 query, I can get all the recipe and item ids I need, then query those after in one big batch!

		// TODO TODO ^ THIS THIS THIS
		//
		//
		//
		//

// 		We have item.id
// 		we want all recipe.item_id matching, now we have recipe.id
// 		then we want all recipe_reagents whose recipe_id matches, pulling in those item_ids

	// SELECT rr.recipe_id, rr.item_id
	// FROM recipe r
	// JOIN recipe_reagents rr ON rr.recipe_id = r.id
	// WHERE r.item_id IN (10676,10723,15732,6139,12543,5804,8104)

	// SELECT rr.recipe_id, rr.item_id
	// FROM recipe r
	// JOIN recipe_reagents rr ON rr.recipe_id = r.id
	// JOIN cte ON cte.item_id = r.item_id

	// SELECT recipe_reagents.recipe_id, recipe_reagents.item_id
	// FROM recipe
	// JOIN recipe_reagents ON recipe_reagents.recipe_id = recipe.id
	// WHERE recipe.item_id IN (10676,10723,15732,6139,12543,5804,8104)

// PATH could be Recipe IDs?
// Select item ids?

// WITH RECURSIVE cte AS
// (
// 	## Seed Select
//   SELECT category_id, name, CAST(category_id AS CHAR(200)) AS path
//   FROM category WHERE parent IS NULL
//   UNION ALL
//   ## Recursive Select
//   SELECT c.category_id, c.name, CONCAT(cte.path, ",", c.category_id)
//   FROM category c JOIN cte ON cte.category_id=c.parent
// )
// SELECT * FROM cte ORDER BY path;
// +-------------+----------------------+---------+
// | category_id | name                 | path    |
// +-------------+----------------------+---------+
// |           1 | ELECTRONICS          | 1       |
// |           2 | TELEVISIONS          | 1,2     |
// |           3 | TUBE                 | 1,2,3   |
// |           4 | LCD                  | 1,2,4   |
// |           5 | PLASMA               | 1,2,5   |
// |           6 | PORTABLE ELECTRONICS | 1,6     |
// |          10 | 2 WAY RADIOS         | 1,6,10  |
// |           7 | MP3 PLAYERS          | 1,6,7   |
// |           8 | FLASH                | 1,6,7,8 |
// |           9 | CD PLAYERS           | 1,6,9   |
// +-------------+----------------------+---------+



		$items = Item::withTranslation()
			->with(
				'recipes'/*,
					'ingredientsOf.ingredients'/*,
				'npcs',
					'npcs.zones',
				'nodes',
					'nodes.zones',
				'rewardedFrom',
					'rewardedFrom.zones',
				'zones'*/
			)
			->whereIn('id', $itemIds->diff($this->lineup['items']))
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
			\Log::info('Adding item ' . $item->id . ' ' . $item->name);
			if ( ! isset($this->lineup['items'][$item->id]))
			{
				$this->lineup['items'][$item->id] = $item;

				// Loop through this item's recipes
				foreach ($item->recipes as $recipe)
				{
					\Log::info('Adding Recipe ' . $recipe->id);
					if ( ! isset($this->lineup['recipes'][$recipe->id]))
					{
						$this->lineup['recipes'][$recipe->id] = $recipe;
						// dd($recipe->product->name, $recipe->ingredients->pluck('name'));
						\Log::info('Looping through recipe items');
						$this->recursiveItemDiscovery($recipe->ingredients->pluck('id'));
						// dd($item->id, $item->name, $recipe->item_id, $recipe->ingredients->pluck('name')->toArray());
					}
				}
			}

		}
	}


}
