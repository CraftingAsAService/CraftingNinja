<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Http\Resources\Json\Items as ItemsCollection;

use App\Models\Game\Aspects\Item;

use App\Models\Game\Concepts\Lists;

class ListsController extends Controller
{

	/**
	 * Show's My Lists
	 * 	Get all of the user's lists
	 */
	public function index()
	{
		// $user = auth()->user();

		// if ( ! $user)
		// 	return response()->json([
		// 		'error' => 'No User Detected, Log In'
		// 	]);

		// //
		// // Lists::fromUser($user->id);
		// dd($user->id);
	}

	/**
	 * Show User's List
	 * @param  Request $request [description]
	 * @param  [type]  $listId  [description]
	 * @return [type]           [description]
	 */
	public function show(Request $request, $userId)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$items = Item::withTranslation()->with('category', 'recipes', 'equipment', 'equipment.jobGroups.job', 'recipes.job')
			->filter($request->all())
			->simplePaginate();

		return new ListsCollection($items);
	}

	private function validator($data)
	{
		return Validator::make($data, [
			'sorting' => [
				Rule::in(['name', 'ilvl'])
			],
			'ordering' => [
				Rule::in(['asc', 'desc'])
			],
		]);
	}

}
