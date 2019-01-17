<?php

namespace App\Http\Controllers;

use App\Models\Game\Concepts\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{

	public function create(Request $request)
	{
		if ( ! auth()->check())
			abort(404);

		$request->validate([
			'id' => 'required|numeric',
			'type' => 'required|string',
			'reason' => 'string',
		]);

		$id = $request->get('id');
		$type = $request->get('type');
		$reason = $request->get('reason');

		Report::record($id, $type, $reason);

		return response()->json([ 'success' => true ]);
	}

}
