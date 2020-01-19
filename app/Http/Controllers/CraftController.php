<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Job;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Aspects\Zone;
use App\Models\Game\Concepts\Map;
use App\Models\Game\Concepts\Sling;
use Illuminate\Http\Request;
use Str;

class CraftController extends Controller
{

	public function index()
	{
		$cookieContents = Sling::parseCookie();

		if ($cookieContents->isEmpty())
			return redirect('sling');

		// If the user had these items marked as a recipe, pre-select it as a recipe
		$recipes = $cookieContents->filter(function($row) {
			return $row['type'] == 'recipe';
		});
		$preferredRecipeIds = $recipes->pluck('id');

		// Users can add items themselves
		//  AND obviously recipes produce items
		$quantities = $cookieContents->mapWithKeys(function($row) {
			if ($row['type'] == 'recipe')
				return [ $row['item_id'] => $row['quantity'] ];
			if ($row['type'] == 'item')
				return [ $row['id'] => $row['quantity'] ];
			return [ '' => null ];
		})->diff([null]);

		$givenItemIds = $quantities->keys();

		// If it's not a digit, or a comma, take it out.
		//  A form of XSS prevention
		$givenItemIdsSearch = preg_replace('/[^\d,]/', '', $givenItemIds->implode(','));

		$results = collect(\DB::select(
			'WITH RECURSIVE cte AS (' .
				'SELECT rr.recipe_id, rr.item_id, 1 AS depth ' .
				'FROM recipes r ' .
				'JOIN item_recipe rr ON rr.recipe_id = r.id ' .
				'WHERE r.item_id IN (' . $givenItemIdsSearch . ') ' .
				'UNION ALL ' .
				'SELECT rr.recipe_id, rr.item_id, cte.depth + 1 ' .
				'FROM recipes r ' .
				'JOIN item_recipe rr ON rr.recipe_id = r.id ' .
				'JOIN cte ON cte.item_id = r.item_id ' .
			') SELECT recipe_id, item_id, depth FROM cte;'
		));

		$recipeIds = $results->pluck('recipe_id')->unique();
		$itemIds = $results->pluck('item_id')
			->unique()
			// Doing things in this order allows the user to manually add and look for Crystals/etc
			->diff(config('game.reagentsToIgnore')) // Take out Crystals/etc, anything not worth accounting for
			->merge($givenItemIds); // Add in what was originally searched upon

		$recipes = Recipe::with(['ingredients' => function($query) {
				$query->whereNotIn('item_id', config('game.reagentsToIgnore'));
			}])
			->whereIn('id', $recipeIds)
			->get()
			->keyBy('id');

		$recipeJobs = Job::withTranslation()
			->whereIn('id', $recipes->pluck('job_id')->unique())
			->get()
			->filter(function($job) use ($recipes) {
				return $recipes->filter(function($recipe) use ($job) {
					return $recipe->job_id == $job->id;
				})->count();
			})->keyBy('id');

		// We want to sort recipes by their depth, but they might appear at multiple depths
		//  The higher the depth, we want it to show up first, but also make sure they only show up once per list.
		$recipeDepths = [];
		foreach ($results->groupBy('depth') as $depth => $recipesAtThisDepth)
			foreach ($recipesAtThisDepth->pluck('recipe_id')->unique() as $recipeId)
				$recipeDepths[$recipeId] = $depth;
		arsort($recipeDepths);
		// Put the recipes in their depth-order
		$recipes = collect(array_replace($recipeDepths, $recipes->toArray()));

		$items = Item::with(
				'nodes', // Gathering drops
					'nodes.zones',
				'mobs', // Mob drops
					'mobs.zones',
				'repeatablyRewardedFrom', // Objective drops
					'repeatablyRewardedFrom.zones',
				'shops',
					'shops.zones',
				'zones', // Treasure drops
				'prices',
			)
			->whereIn('id', $itemIds)
			->get()
			->keyBy('id');

		// Break down item availability by zone

		// Globalizing zones temporarily; works better in the mapWithKeys functions
		$this->zones = $items->pluck('zones')->flatten()->pluck('id');

		$rewards = $items->pluck('repeatablyRewardedFrom')->flatten()->keyBy('id')->mapWithKeys(function($entry) {
			$this->zones = $this->zones->merge($entry->zones->pluck('id'));
			return [$entry->id => [
				'id'    => $entry->id,
				'level' => $entry->level,
				'icon'  => $entry->icon,
				'name'  => $entry->name,
			]];
		});

		$mobs = $items->pluck('mobs')->flatten()->keyBy('id')->mapWithKeys(function($entry) {
			$this->zones = $this->zones->merge($entry->zones->pluck('id'));
			return [$entry->id => [
				'id'    => $entry->id,
				'level' => $entry->level,
				'name'  => ucwords($entry->name),
			]];
		});

		$nodes = $items->pluck('nodes')->flatten()->keyBy('id')->mapWithKeys(function($entry) {
			$this->zones = $this->zones->merge($entry->zones->pluck('id'));
			return [$entry->id => [
				'id'    => $entry->id,
				'level' => $entry->level,
				'type'  => $entry->type,
				'name'  => $entry->name,
			]];
		});

		$shops = $items->pluck('shops')->flatten()->keyBy('id')->mapWithKeys(function($entry) {
			$this->zones = $this->zones->merge($entry->zones->pluck('id'));
			return [$entry->id => [
				'id'    => $entry->id,
				'level' => $entry->level,
				'type'  => $entry->type,
				'name'  => $entry->name,
			]];
		});

		$zones = Zone::with('parent', 'translations')
			->whereIn('id', $this->zones->unique())
			->get()
			->keyBy('id');
		unset($this->zones);

		$relevantMaps = Map::with('detail')
			->whereIn('zone_id', $zones->pluck('id'))
			->get();

		$maps = [];
		foreach ($relevantMaps as $map)
		{
			if ( ! isset($map->detail->data))
				continue;

			if ( ! isset($maps[$map->zone_id]))
				$maps[$map->zone_id] = [];
			$maps[$map->zone_id][] = array_merge($map->detail->data, [ 'image' => $map->image ]);
		}

		$loopVars = [
			// $item->$key => 'shorthandKey'
			'nodes' => 'nodes',
			'mobs' => 'mobs',
			'shops' => 'shops',
			'repeatablyRewardedFrom' => 'rewards',
		];

		$breakdown = [
			// ZoneID => [
			// 	 ItemID => [
			// 	   'nodes' => [
			// 	     NodeID => [ Coordinate Data ],
			// 	   ],
			// 	   'shops' => [],
			// 	   'rewards' => [],
			// 	   'mobs' => [],
			// 	   'treasure' => [],
			// 	 ]
			// ]
		];
		foreach ($items as $item)
		{
			foreach ($loopVars as $key => $slug)
				foreach ($item->$key as $var)
					foreach ($var->zones as $zone)
						$breakdown[$zone->id][$item->id][Str::singular($slug)][$var->id] = [
							'x'      => $zone->pivot->x,
							'y'      => $zone->pivot->y,
							'radius' => $zone->pivot->radius,
						];

			foreach ($item->zones as $zone)
				$breakdown[$zone->id][$item->id]['treasure'][] = [
					'x'      => $zone->pivot->x,
					'y'      => $zone->pivot->y,
					'radius' => $zone->pivot->radius,
				];

			// TODO Did this item have no location entries?? Make an "unknown" location
		}

		// Zone with the most items goes first
		$breakdown = collect($breakdown)->sortByDesc(function($entries) {
			return count($entries);
		});

		// Compress variables for JavaScript
		//  TODO Convert to Resources
		$zones = $zones->map(function($zone) {
			return [
				'id'       => $zone->id,
				'parentId' => $zone->zone_id,
				'name'     => $zone->fullName,
			];
		});
		$items = $items->map(function($item) {
			return [
				'id'     => $item->id,
				'name'   => $item->name,
				'rarity' => $item->rarity,
				'icon'   => $item->icon,
			];
		});

		// Convert maps to the Ninja Maps data structure
		$maps = $this->buildNinjaMapsArray($breakdown, $maps, $zones, $nodes);
		return view('game.craft', compact('preferredRecipeIds', 'givenItemIds', 'quantities', 'breakdown', 'items', 'recipes', 'nodes', 'zones', 'rewards', 'mobs', 'shops', 'maps', 'recipeJobs'));
	}

	private function buildNinjaMapsArray($breakdown, $maps, $zones, $nodes)
	{
		$ninjaMaps = [];

		foreach ($breakdown as $zoneId => $itemIds)
		{
			if ( ! isset($maps[$zoneId]))
				continue;

			$floor = 0;

			foreach ($maps[$zoneId] as $data)
			{
				$mapRecord = [
					'id'     => 'zone' . $zoneId . '-' . $floor,
					'name'   => $zones[$zoneId]['name'],
					'src'    => '/assets/' . config('game.slug') . '/m/' . $data['image'] . '.jpg',
					// Bounds goes from 1,1 to 44,44 (as opposed to 0,0 to x,y)
					// anything less than 1,1 is unreachable, and likewise 44,44 itself is unreachable
					'bounds' => [[1, 1], [44, 44]],
					'size'   => $data['size'],
					'offset' => [
						'x' => $data['offset']['x'] ?: 0,
						'y' => $data['offset']['y'] ?: 0
					],
					'floor' => $floor,
					'markers' => [],
				];

				foreach ($itemIds as $itemData)
					foreach ($itemData['nodes'] ?? [] as $nodeId => $data)
						$mapRecord['markers'][] = [
							'id'      => 'node' . $nodeId . '@' . $zoneId,
							'tooltip' => __('Level') . ' ' . $nodes[$nodeId]['level'] . ' ' . config('game.nodeTypes')[$nodes[$nodeId]['type']]['name'],
							'x'       => $data['x'] ?: 0,
							'y'       => $data['y'] ?: 0,
							'icon'    => '/assets/' . config('game.slug') . '/map/icons/' . config('game.nodeTypes')[$nodes[$nodeId]['type']]['icon'] . '.png',
						];

				$ninjaMaps[] = $mapRecord;

				$floor++;
			}
		}

		return $ninjaMaps;
	}


}
