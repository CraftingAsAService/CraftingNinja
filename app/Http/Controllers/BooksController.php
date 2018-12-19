<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Listing;
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
		$book = Listing::with('items')->published()->findOrFail($bookId);
		return view('game.books.show', compact('book'));
	}

}
