<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Json\Books as BooksCollection;
use App\Models\Game\Concepts\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;


class BooksController extends Controller
{

	public function index(Request $request)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$books = Listing::withTranslation()->with('jottings', 'voteTally')
			->filter($request->all())
			->simplePaginate();

		return new BooksCollection($items);
	}

	private function validator($data)
	{
		return Validator::make($data, [
			'sorting' => [
				Rule::in(['name'])
			],
			'ordering' => [
				Rule::in(['asc', 'desc'])
			],
		]);
	}

}
