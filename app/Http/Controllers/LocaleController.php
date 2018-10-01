<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{

	public function set(Request $request, $locale = 'en')
	{
		if (in_array($locale, config('translatable.locales')))
			session()->put('locale', $locale);
		return redirect()->back();
	}

}
