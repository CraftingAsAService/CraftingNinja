<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider {

	public function register()
	{
		require_once(app_path() . '/Helpers/ViewHelper.php');
		// TODO cache this?  Config it?
		// foreach (glob(app_path().'/Helpers/*.php') as $filename){
		// 	require_once($filename);
		// }
	}
}
