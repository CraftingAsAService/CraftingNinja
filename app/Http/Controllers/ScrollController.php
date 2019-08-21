<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Job;
use App\Models\Game\Concepts\Sling;
use App\Models\Game\Concepts\Scroll;
use App\Models\Game\Concepts\Scroll\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ScrollController extends Controller
{

	public function create()
	{
		$ninjaCart = Sling::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('sling');

		$jobs = Job::byTypeAndTier();

		return view('game.scrolls.create', compact('ninjaCart', 'jobs'));
	}

	public function store(Request $request)
	{
		$ninjaCart = Sling::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('sling');

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

		$scroll = Scroll::create([
			'user_id'        => auth()->user()->id,
			'name:en'        => $request->input('name'),
			'description:en' => $request->input('description'),
			'job_id'         => $request->input('job_id'),
			'min_level'      => $request->input('min_level'),
			'max_level'      => $request->input('max_level'),
		]);

		foreach (Scroll::$polymorphicRelationships as $scrollType)
		{
			$entities = $ninjaCart->filter(function($entity) use ($scrollType) {
				return $entity['type'] == Str::singular($scrollType);
			});
			$model = 'App\\Models\\Game\\Aspects\\' . ucwords(Str::singular($scrollType));

			foreach ($entities as $entity)
				$scroll->$scrollType()->attach($model::find($entity['id']), ['quantity' => $entity['quantity']]);
		}

		Sling::unsetCookie();

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
		$scrolls = Scroll::published()->get();

		return view('game.scrolls', compact('scrolls'));
	}

	/**
	 * View a single scroll
	 *
	 * @param	type	$scrollId
	 * @return	view
	 */
	public function show($scrollId)
	{
		$scrollPolymorphicRelationships = Scroll::$polymorphicRelationships;
		$scroll = Scroll::with('items')->findOrFail($scrollId);

		return view('game.scrolls.show', compact('scroll', 'scrollPolymorphicRelationships'));
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

		$scroll = Scroll::with('votes')->findOrFail($scrollId);

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
	 * All all of this scroll's entries to the user's active sling
	 */
	public function addAllEntriesToSling(Request $request, $scrollId)
	{
		if ( ! auth()->check())
			return $this->respondWithError(401, 'Unauthenticated');

		$scroll = Scroll::with(array_values(Scroll::$polymorphicRelationships))->findOrFail($scrollId);
		$sling = new Sling;

		foreach (Scroll::$polymorphicRelationships as $relation)
			foreach ($scroll->$relation as $entity)
				$sling->change($entity->id, str_singular($relation), $entity->pivot->quantity);
	}

}
