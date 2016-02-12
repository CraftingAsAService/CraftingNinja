<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FlyoutsController extends Controller
{

	public function loadPanel($panel = '')
	{
		if ( ! file_exists(base_path() . '/resources/views/flyouts/' . $panel . '.blade.php'))
			abort(404);
		
		return view('flyouts.' . $panel);
	}

}
