<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Game;

class DetectGame
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
		$gameSlug = array_values( // Reset Keys
			array_diff( // Remove Dev & QA subdomains
				array_slice( // Remove "craftingasaservice" and "com"
					explode('.', parse_url($request->root())['host']) // e.g. returns http://dev.ffxiv.craftingasaservice.com
					, 0, -2
				)
				, ['dev', 'qa']
			)
		);

		if (count($gameSlug))
			config([
				'gameSlug' => $gameSlug[0],
				'game' => Game::whereSlug($gameSlug[0])->first()
			]);

		return $next($request);
	}
}
