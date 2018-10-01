<?php

namespace App\Http\Middleware;

use Closure;

class DetectLocale
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
		$locale = $request->header('x-locale') ?? session()->get('locale', 'en');

		if (in_array($locale, config('translatable.locales')))
			\App::setLocale($locale);

		return $next($request);
	}
}
