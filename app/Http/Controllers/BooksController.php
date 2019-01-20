<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Knapsack;
use App\Models\Game\Concepts\Listing;
use App\Models\Game\Concepts\Listing\Vote;
use Illuminate\Http\Request;

class BooksController extends Controller
{
	//

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
	 * Publish Books.
	 *
	 * @param	type	$bookId
	 * @return	JSON
	 */
	public function publish(Request $request, $bookId)
	{
		if ( ! \Auth::check())
			return $this->respondWithError(401, 'Unauthenticated');

		$validator = \Validator::make($request->all(), [
			'dir' => 'required|in:1,-1',
		]);

		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$book = Listing::with('votes')->findOrFail($bookId);

		if ( ! $book->published_at && $request->input('dir') == 1)
			$book->publish(true);
		else if ($book->published_at && $request->input('dir') == -1)
			$book->publish(false);

		return response()->json([ 'published' => (boolean) $book->published_at ]);
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
