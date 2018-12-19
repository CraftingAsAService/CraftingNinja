<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Json\Items as ItemsCollection;

use App\Models\Game\Aspects\Item;

use App\Models\Game\Concepts\Lists;

use Carbon\Carbon;

class KnapsackController extends Controller
{

	public function index(Request $request)
	{
		$contents = $this->getCookieContents();

		if (isset($contents['item']))
		{
			$data = new ItemsCollection(Item::withTranslation()->with('category', 'recipes', 'equipment', 'equipment.jobGroups.job', 'recipes.job')->whereIn('id', array_keys($contents['item']))->get());

			$contents['item'] = [
				'qtys' => $contents['item'],
				'data' => json_decode($data->toJson(), true),
			];
		}

		$lists = Lists::fromUser()->get();

		return view('game.knapsack', compact('contents', 'lists'));
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
