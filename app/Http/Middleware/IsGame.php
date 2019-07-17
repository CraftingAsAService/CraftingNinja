<?php

namespace App\Http\Middleware;

use Closure;

class IsGame
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// Make sure the request has a game selected
		if ( ! config('game.slug'))
		{
			flash('Please select a game!')->warning();
			return redirect('/');
		}

		// No longer looking for {game}! Letting DetectGame take the lead on it
		// Route::domain('{game}.' . config('app.base_url'))->
		// // If it is a game, fuhgeddaboudit
		// $request->route()->forgetParameter('game');

		return $next($request);
	}
}
