<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{

	public function switchLanguage(Request $request, $lang)
    {
        if (array_key_exists($lang, config('languages')))
        {
            $request->session()->set('applocale', $lang);

            // Save the user's preference
            if (\Auth::check())
            {
	            $language_id = \App\Models\Language::where('code', $lang)->first()->id;
	        	\Auth::user()->language_id = $language_id;
	        	\Auth::user()->save();
            }
        }


        return redirect()->back();
    }

}
