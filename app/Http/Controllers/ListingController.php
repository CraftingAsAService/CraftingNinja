<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{

	/**
	 * Toggle published attribute of a listing
	 *
	 * @param	type	$listingId
	 * @return	JSON
	 */
	public function publish(Request $request, $listingId)
	{
		if ( ! \Auth::check())
			return $this->respondWithError(401, 'Unauthenticated');

		$validator = \Validator::make($request->all(), [
			'dir' => 'required|in:1,-1',
		]);

		if ($validator->fails())
			return $this->respondWithError(422, $validator->errors());

		$book = Listing::with('votes')->fromUser()->findOrFail($listingId);

		if ( ! $book->published_at && $request->input('dir') == 1)
			$book->publish(true);
		else if ($book->published_at && $request->input('dir') == -1)
			$book->publish(false);

		return response()->json([ 'published' => (boolean) $book->published_at ]);
	}

	/**
	 * Delete a listing.
	 *
	 * @param	integer	$listingId
	 * @return	JSON
	 */
	public function delete(Request $request, $listingId)
	{
		if ( ! \Auth::check())
			return $this->respondWithError(401, 'Unauthenticated');

		$listing = Listing::fromUser()->unpublished()->findOrFail($listingId);

		$listing->delete();

		return response()->json([ 'deleted' => true ]);
	}

}
