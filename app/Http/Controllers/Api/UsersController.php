<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Http\Resources\Json\Lists as ListsCollection;

use App\Models\Game\Aspects\Item;
use App\Models\Game\Concepts\Lists;

use App\Models\User;

class UsersController extends Controller
{

	/**
	 * Get lists belonging to a specific user
	 * 	/users/$user/lists
	 * 	If user isn't numeric, we display results for the logged in user
	 * 	Only these results will include the cookied and private lists
	 */
	public function lists($user)
	{
		$userIsMe = ! is_numeric($user);

		$user = $userIsMe ? auth()->user() : User::whereId($user)->firstOrFail();

		if ( ! $user)
			return response()->json([
				'error' => 'No User Detected, Log In'
			]);

		$lists = Lists::fromUser($user->id)->publicOnly( ! $userIsMe)->matchLocale()->get();

		return new ListsCollection($lists);
	}

}
