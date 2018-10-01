<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Json\Items as ItemsCollection;

use App\Models\Game\Aspects\Item;

class KnapsackController extends Controller
{

	public function index(Request $request)
	{
		$contents = null;
		// Detect cookie contents,
		// 	cookie was set by JS, and laravel expects it to be encrypted
		// 	so we use $_COOKIE directly
		if ($cookie = $_COOKIE[config('game.slug') . '-active-list'] ?? false)
		{
			$contents = json_decode(base64_decode($cookie), true);
			if (isset($contents['item']))
			{
				$data = new ItemsCollection(Item::withTranslation()->with('category', 'recipes', 'equipment', 'equipment.jobGroups.job', 'recipes.job')->whereIn('id', array_keys($contents['item']))->get());

				$contents['item'] = [
					'qtys' => $contents['item'],
					'data' => json_decode($data->toJson(), true),
				];
			}
		}

		return view('game.knapsack', compact('contents'));
	}

}
