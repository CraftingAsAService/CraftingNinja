<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Game\Aspects\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ItemsController extends Controller
{

	public function index(Request $request)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$items = Item::withTranslation()->with('category', 'recipes', 'equipment', 'equipment.niche.jobs', 'recipes.job')
			->filter($request->all())
			->simplePaginate($request->get('perPage'));

		return ItemResource::collection($items);
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
			'perPage' => [
				Rule::in(['', 15, 30, 45])
			],
		]);
	}

}
