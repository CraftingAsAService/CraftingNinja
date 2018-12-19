<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Book;
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
		$books = Book::all();
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
		$book = Book::with('items')->findOrFail($bookId);
		return view('game.books.show', compact('book'));
	}

}
