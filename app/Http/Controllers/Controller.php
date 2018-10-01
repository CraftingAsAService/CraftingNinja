<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * Handles an error response formatting it according to our spec.
	 *
	 * @param array $error
	 * @param array $headers
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function respondWithError($errorStatusCode, $error, $headers = [])
	{
		return response()->json(['errors' => $error])->setStatusCode($errorStatusCode);
	}

}
