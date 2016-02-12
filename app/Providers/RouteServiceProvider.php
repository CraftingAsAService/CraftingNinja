<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        // $router->pattern('errornumber', '[0-9]{3}');

        parent::boot($router);

        // If this is a restore request, this alters the route model binding to include trashed items
        // Test if it's set for the benefit of the CLI
        // $is_restore = strstr(\Request::url(), '/restore');

        $bindings = [
            // 'users' => 'App\User',

        ];

        // // Keep it simple if it's not a restore request
        // if ( ! $is_restore)
            foreach ($bindings as $bind => $model)
                $router->model($bind, 'App\Models\\' . $model);
        // // If it is a restore request, only look for trashed items
        // else
        //     foreach ($bindings as $bind => $model)
        //         $router->bind($bind, function($value) use ($model) {
        //             $model = '\App\Models\\' . $model;
        //             $model = new $model;
        //             return $model->onlyTrashed()->where('id', $value)->firstOrFail();
        //         });
    }



    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
