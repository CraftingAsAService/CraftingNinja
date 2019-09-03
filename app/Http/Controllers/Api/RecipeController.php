<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Game\Aspects\Recipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class RecipeController extends Controller
{

	public function index(Request $request)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$recipes = Recipe::withTranslation()->with('job', 'product')
			->filter($request->all())
			->simplePaginate($request->get('perPage'));

		return RecipeResource::collection($recipes);
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
