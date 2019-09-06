<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScrollResource;
use App\Models\Game\Concepts\Scroll;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ScrollController extends Controller
{

	public function index(Request $request)
	{
		$validator = $this->validator($request->all());
		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$scrolls = Scroll::withTranslation()->withCount('votes')->with('myVote', 'job', 'author', 'items', 'objectives', 'recipes', 'nodes')
			->filter($request->all())
			->simplePaginate();

		return ScrollResource::collection($scrolls);
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
			'perPage' => [
				Rule::in(['', 15, 30, 45, 60])
			],
		]);
	}

}
