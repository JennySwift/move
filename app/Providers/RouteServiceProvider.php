<?php

namespace App\Providers;

use App\Models\Entry;
use App\Models\Exercise;
use App\Models\Series;
use App\Models\Unit;
use App\Models\Workout;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::bind('exercise', function($id)
        {
            return Exercise::forCurrentUser()->findOrFail($id);
        });

        Route::bind('series', function($id)
        {
            return Series::forCurrentUser()->findOrFail($id);
        });

        Route::bind('unit', function($id)
        {
            return Unit::forCurrentUser()->findOrFail($id);
        });

        Route::bind('entry', function ($id) {
            return Entry::forCurrentUser()->findOrFail($id);
        });

        Route::bind('workout', function ($id) {
            return Workout::forCurrentUser()->findOrFail($id);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
