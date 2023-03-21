<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application modules.
     *
     * @return void
     */
    public function register()
    {
        Paginator::defaultView('frontend.include.__pagination');

    }

    /**
     * Bootstrap any application modules.
     *
     * @return void
     */
    public function boot()
    {

        $timezone = setting('site_timezone','global');
        config()->set([
            'app.timezone' => $timezone,
            'app.debug' => setting('debug_mode','permission'),
        ]);
        date_default_timezone_set($timezone);

    }
}
