<?php

namespace App\Http\Middleware;

use Closure;

class Game
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
        // If the Game ID is stored
        if ($request->session()->has('gid'))
        {
            // If I share a link to ffxiv, but my friend is a user who frequents the wow section
            //  we need to switch over.  Confirm session slug differs from route slug,
            //  and confirm route slug is valid, and switch
            $route_slug = app()->router->getCurrentRoute()->parameter('game_prefix');
            if ($route_slug != session('game-slug'))
            {
                $game = \App\Models\Game::where('slug', session('game-slug'))->first();
                if (isset($game->exists))
                {
                    session([
                        'gid' => $game->id,
                        'game-slug' => $game->slug,
                    ]);
                }
            }

            // In any event, transfer the selected game into the temporary config
            config([
                'gid' => session('gid'),
                'game-slug' => session('game-slug'),
            ]);
        }

        return $next($request);
    }
}
