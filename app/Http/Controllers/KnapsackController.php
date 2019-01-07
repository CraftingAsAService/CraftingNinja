<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Aspects\Node;
use App\Models\Game\Aspects\Objective;
use App\Models\Game\Aspects\Recipe;
use App\Models\Game\Concepts\Listing;
use Illuminate\Http\Request;

class KnapsackController extends Controller
{

	public function index()
	{
		$listings = Listing::with('job', 'votes', 'items', 'recipes', 'recipes.product', 'objectives')->fromUser()->get();

		return view('game.knapsack', compact('listings'));
	}

	public function addActiveEntry(Request $request)
	{
		if ( ! auth()->check())
			abort(404);

		$request->validate([
			'id' => 'required|numeric',
			'type' => 'required|string',
			'quantity' => 'numeric',
		]);

		$id = $request->get('id');
		$type = $request->get('type');
		$relation = str_plural($type);
		$quantity = $request->get('quantity', 1);

		// Find the entity we're trying to add or update
		$allowableClasses = [
			'item' => new Item,
			'recipe' => new Recipe,
			'node' => new Node,
			'objective' => new Objective,
		];

		// Find the entity, or fail
		$entity = $allowableClasses[$type]::findOrFail($id);

		// Get the active list, create one if it does not exist
		$listing = Listing::with($relation)->active()->firstOrCreate([
			'user_id' => auth()->user()->id,
		]);

		// Attach the entity
		$listing->$relation()->attach($entity, [ 'quantity' => $quantity ]);

		return response()->json([ 'success' => true ]);
	}

	public function removeActiveEntry(Request $request)
	{
		if ( ! auth()->check())
			abort(404);

		$request->validate([
			'id' => 'required|numeric',
			'type' => 'required|string',
		]);

		$id = $request->get('id');
		$relation = str_plural($request->get('type'));

		// Get the active list, fail if one does not exist
		$listing = Listing::with($relation)->active()->firstOrFail();

		// Find the entry we're trying to remove
		$entry = $listing->$relation->where('id', $id)->first();

		// Detach that entry
		$listing->$relation()->detach($entry);

		return response()->json([ 'success' => true ]);
	}

	public function publish(Request $request)
	{
		if ( ! auth()->check())
			abort(404);

		// Not worried about duplicates
		$request->validate([
			'name' => 'required|max:255',
			'description' => 'max:255',
		]);

		$newList = $request->except('_token');

		// Throttle user list creation, to be safe from double submits and general mischief
		if ($lastList = Lists::usersLastEntry()->first())
		{
			// Take the time from the lastList and add the threshold (30 seconds).
			// Is the current time greater than that? Proceed.
			if ( ! Carbon::now()->greaterThanOrEqualTo($lastList->created_at->addSeconds(30)))
			{
				flash('Please wait 30 seconds between submissions!')->warning();
				return redirect()->back()->withInput($newList);
			}
		}

		$newList['contents'] = $this->getCookieContents();

		$newList = Lists::create($newList);

		// Delete current list/cookie
		\Cookie::queue($this->getCookieName(), null, -1);

		flash('Enjoy your new list!')->success();
		return redirect()->back();
	}

	public function getCookieName()
	{
		return config('game.slug') . '-active-list';
	}

	public function getCookieContents()
	{
		// Detect cookie contents,
		// 	cookie was set by JS, and laravel expects it to be encrypted
		// 	so we use $_COOKIE directly
		if ($cookie = $_COOKIE[$this->getCookieName()] ?? false)
			return json_decode(base64_decode($cookie), true);

		return null;
	}

}
