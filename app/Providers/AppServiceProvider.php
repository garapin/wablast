<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		if ($this->app->environment('production')) {
<<<<<<< HEAD
			\URL::forceScheme('https');
=======
			URL::forceScheme('https');
>>>>>>> fa82136 (commit AppServiceProvider. hilangkan \ pada URL)
		}
       Paginator::useBootstrap();
       Model::preventLazyLoading(true);
    }
}
