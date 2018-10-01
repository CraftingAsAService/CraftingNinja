<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Http\Resources\Json\Items as ItemsCollection;

use App\Models\Game\Aspects\Item;

class ItemsController extends Controller
{

	public function index(Request $request)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$items = Item::withTranslation()->with('category', 'recipes', 'equipment', 'equipment.jobGroups.job', 'recipes.job')
			->filter($request->all())
			->simplePaginate();

		return new ItemsCollection($items);
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
