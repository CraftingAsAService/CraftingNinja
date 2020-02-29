<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Cache;
use Closure;

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
		// If the request has `game` set, use it, otherwise split the URL to figure it out
		$gameSlug = preg_replace('/^(.+?)\.?' . preg_quote(config('app.base_url'), '/') . '$/', '$1', parse_url($request->root())['host']);

		if (in_array($gameSlug, config('games.valid')))
		{
			\DB::setDefaultConnection($gameSlug);

			// config(['app.url' => str_replace('//', '//' . $gameSlug . '.', config('app.url'))]);

			config(['game' => array_merge(
				[
					'slug' => $gameSlug,
					'data' => Cache::remember('game-' . $gameSlug . '-' . app()->getLocale(), 1440, function() use ($gameSlug) {
							$game = Game::withTranslation()->whereSlug($gameSlug)->get()->first();

							return [
								'name'         => $game->name,
								'slug'         => $game->slug,
								'abbreviation' => $game->abbreviation,
								'version'      => $game->version,
								'description'  => $game->description,
							];
						})
				],
				config('games.' . $gameSlug, [])
			)]);
		}

		return $next($request);
	}
}
