<?php

namespace App\Providers;

use App\Models\LandingPage;
use App\Models\Navigation;
use App\Models\Page;
use App\View\SiteCurrencyComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register modules.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap modules.
     *
     * @return void
     */
    public function boot()
    {

        View::composer(['backend.setting.site_setting.include.__global'], SiteCurrencyComposer::class);

        View::composer(['backend.include.__side_nav'], function ($view) {
            $view->with([
                'landingSections' => cache()->remember('landingSections', 60 * 60 * 24, function () {
                    return LandingPage::whereNot('code', 'footer')->get();
                }),
                'pages' => cache()->remember('pages', 60 * 60 * 24, function () {
                    return Page::all();
                })
            ]);
        });


        View::composer(['frontend.include.__header'], function ($view) {
            $view->with([
                'navigations' => Navigation::where('status', 1)->where(function ($query) {
                    $query->where('type', 'header')
                        ->orWhere('type', 'both');

                })->orderBy('header_position')->get()
            ]);
        });

        View::composer(['frontend.include.__footer'], function ($view) {
            $view->with([
                'navigations' => Navigation::where('status', 1)->where(function ($query) {
                    $query->where('type', 'footer')
                        ->orWhere('type', 'both');

                })->orderBy('footer_position')->get()->chunk(4)
            ]);
        });

        View::composer(['*'], function ($view) {
            $view->with([
                'currencySymbol' => setting('currency_symbol', 'global'),
                'currency' => setting('site_currency', 'global'),
            ]);
        });
        if (auth('web')) {
            View::composer(['frontend*',], function ($view) {
                $view->with('user', auth()->user());
            });
        }


    }
}
