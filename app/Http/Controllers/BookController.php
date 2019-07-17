<?php

namespace App\Http\Controllers;

use App\Models\Game\Aspects\Job;
use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Listing\Vote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{

	public function create()
	{
		$ninjaCart = Knapsack::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('knapsack');

		return view('game.books.create', compact('ninjaCart'));
	}

	public function store(Request $request)
	{
		$ninjaCart = Knapsack::parseCookie();

		if ($ninjaCart->isEmpty())
			return redirect()->route('knapsack');

		$validator = \Validator::make($request->all(), [
			'name' => 'required',
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
			'published_at'   => now(),
		]);

		return redirect()->route('compendium', [
			'chapter' => 'books',
			'filter'  => 'mine',
		]);
	}





	/**
	 * Book Index
	 * @return	view
	 */
	public function index()
	{
		$listings = Listing::published()->get();

		return view('game.books', compact('listings'));
	}

	/**
	 * View a single book
	 *
	 * @param	type	$bookId
	 * @return	view
	 */
	public function show($listingId)
	{
		$listingPolymorphicRelationships = Listing::$polymorphicRelationships;
		$listing = Listing::with('items')->findOrFail($listingId);

		return view('game.books.show', compact('listing', 'listingPolymorphicRelationships'));
	}

	/**
	 * Book Voting, POST
	 *
	 * @param	type	$bookId
	 * @return	JSON
	 */
	public function vote(Request $request, $bookId)
	{
		if ( ! \Auth::check())
			return $this->respondWithError(401, 'Unauthenticated');

		$validator = \Validator::make($request->all(), [
			'dir' => 'required|in:1,0',
		]);

		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$book = Listing::with('votes')->published()->findOrFail($bookId);

		$existingVote = $book->votes()->where('user_id', auth()->user()->id)->first();

		if ( ! $existingVote && $request->input('dir') == 1)
		{
			$vote = Vote::make()->user()->associate(auth()->user());
			$book->votes()->save($vote);
		}
		else if ($existingVote && $request->input('dir') == 0)
			$existingVote->delete();

		return response()->json([ 'votes' => $book->votes()->count() ]);
	}

	/**
	 * All all of this book's entries to the user's active knapsack
	 */
	public function addAllEntriesToKnapsack(Request $request, $bookId)
	{
		if ( ! auth()->check())
			return $this->respondWithError(401, 'Unauthenticated');

		$book = Listing::with(array_values(Listing::$polymorphicRelationships))->findOrFail($bookId);
		$knapsack = new Knapsack;

		foreach (Listing::$polymorphicRelationships as $relation)
			foreach ($book->$relation as $entity)
				$knapsack->change($entity->id, str_singular($relation), $entity->pivot->quantity);
	}

}
