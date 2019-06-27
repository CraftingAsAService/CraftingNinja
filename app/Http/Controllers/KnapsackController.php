<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Listing;
use Illuminate\Http\Request;

class KnapsackController extends Controller
{

	public function index()
	{
		$ninjaCart = $this->parseCookie();
		$listings = Listing::with('job', 'votes', 'items', 'recipes', 'recipes.product', 'objectives')->fromUser()->get();

		return view('game.knapsack', compact('ninjaCart', 'ninjaCartSum', 'listings'));
	}

	private function parseCookie()
	{
		$ninjaCart = isset($_COOKIE['NinjaCart']) && $_COOKIE['NinjaCart']
			? json_decode($_COOKIE['NinjaCart'])
			: [];

		foreach ($ninjaCart as &$entry)
		{
			if ($entry['t'] == 'item')
				// TODO ADD setQuantity FUNCTION
				$entry = Item::whereId($entry['i'])->first()->setQuantity($entry['q']);
		}

		return collect($ninjaCart);
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
		$quantity = $request->get('quantity', 1);

		$knapsack = new Knapsack;
		$knapsack->change($id, $type, $quantity);

		return response()->json([ 'success' => true ]);
	}

	public function updateActiveEntry(Request $request)
	{
		return $this->addActiveEntry($request);
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
		$type = $request->get('type');

		$knapsack = new Knapsack;
		$knapsack->remove($id, $type);

		return response()->json([ 'success' => true ]);
	}

	public function removeAllActiveEntries(Request $request)
	{
		if ( ! auth()->check())
			abort(404);

		$knapsack = new Knapsack;
		$knapsack->truncate();

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
