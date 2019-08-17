<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Job;
use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Scroll\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ScrollController extends Controller
{

	public function create()
	{
		$ninjaCart = Knapsack::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('knapsack');

		$jobs = Job::byTypeAndTier();

		return view('game.scrolls.create', compact('ninjaCart', 'jobs'));
	}

	public function store(Request $request)
	{
		$ninjaCart = Knapsack::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('knapsack');

		$validator = \Validator::make($request->all(), [
			'name' => 'required',
			'description' => 'max:140',
			'job_id' => [
				'numeric',
				Rule::in(Job::pluck('id')->toArray()),
			],
			'min_level' => 'numeric|lte:max_level',
			'max_level' => 'numeric|gte:min_level',
		]);

		if ($validator->fails())
			return redirect()->back()
					->withErrors($validator)
					->withInput();

		$listing = Listing::create([
			'user_id'        => auth()->user()->id,
			'name:en'        => $request->input('name'),
			'description:en' => $request->input('description'),
			'job_id'         => $request->input('job_id'),
			'min_level'      => $request->input('min_level'),
			'max_level'      => $request->input('max_level'),
		]);

		foreach (Listing::$polymorphicRelationships as $listingType)
		{
			$entities = $ninjaCart->filter(function($entity) use ($listingType) {
				return $entity['type'] == Str::singular($listingType);
			});
			$model = 'App\\Models\\Game\\Aspects\\' . ucwords(Str::singular($listingType));

			foreach ($entities as $entity)
				$listing->$listingType()->attach($model::find($entity['id']), ['quantity' => $entity['quantity']]);
		}

		Knapsack::unsetCookie();

		return redirect()->route('compendium', [
			'chapter' => 'scrolls',
			'author'  => auth()->user()->encodedId(),
		]);
	}





	/**
	 * Scroll Index
	 * @return	view
	 */
	public function index()
	{
		$listings = Listing::published()->get();

		return view('game.scrolls', compact('listings'));
	}

	/**
	 * View a single scroll
	 *
	 * @param	type	$scrollId
	 * @return	view
	 */
	public function show($scrollId)
	{
		$listingPolymorphicRelationships = Listing::$polymorphicRelationships;
		$listing = Listing::with('items')->findOrFail($scrollId);

		return view('game.scrolls.show', compact('listing', 'listingPolymorphicRelationships'));
	}

	/**
	 * Scroll Voting, POST
	 *
	 * @param	type	$scrollId
	 * @return	JSON
	 */
	public function vote(Request $request, $scrollId)
	{
		if ( ! \Auth::check())
			return $this->respondWithError(401, 'Unauthenticated');

		$validator = \Validator::make($request->all(), [
			'dir' => 'required|in:1,0',
		]);

		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$scroll = Listing::with('votes')->published()->findOrFail($scrollId);

		$existingVote = $scroll->votes()->where('user_id', auth()->user()->id)->first();

		if ( ! $existingVote && $request->input('dir') == 1)
		{
			$vote = Vote::make()->user()->associate(auth()->user());
			$scroll->votes()->save($vote);
		}
		else if ($existingVote && $request->input('dir') == 0)
			$existingVote->delete();

		return response()->json([ 'votes' => $scroll->votes()->count() ]);
	}

	/**
	 * All all of this scroll's entries to the user's active knapsack
	 */
	public function addAllEntriesToKnapsack(Request $request, $scrollId)
	{
		if ( ! auth()->check())
			return $this->respondWithError(401, 'Unauthenticated');

		$scroll = Listing::with(array_values(Listing::$polymorphicRelationships))->findOrFail($scrollId);
		$knapsack = new Knapsack;

		foreach (Listing::$polymorphicRelationships as $relation)
			foreach ($scroll->$relation as $entity)
				$knapsack->change($entity->id, str_singular($relation), $entity->pivot->quantity);
	}

}
