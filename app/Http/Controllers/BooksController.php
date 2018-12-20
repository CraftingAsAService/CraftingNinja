<?php

namespace App\Http\Controllers;

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
		$books = Listing::published()->get();
		return view('game.books', compact('books'));
	}

	/**
	 * View a single book
	 *
	 * @param	type	$bookId
	 * @return	view
	 */
	public function show($bookId)
	{
		$book = Listing::with('jottings', 'jottings.jottable')->published()->findOrFail($bookId);
		return view('game.books.show', compact('book'));
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

		return response()->json(['votes' => $book->votes()->count()]);
	}

}
