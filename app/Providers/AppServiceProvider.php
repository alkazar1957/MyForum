<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // \View::share('channels', \App\Channel::all() );
        \View::composer('*', function ($view) {
            // \Cache::forget('channels');
            $channels = \Cache::rememberForever('channels', function () {
                $c = new \App\Channel;
                // dd($c->orderBy('name', 'ASC')->pluck('slug'));
                return $c->orderBy('name', 'ASC')->get();
            });
            $view->with('channels', $channels);
        });

        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
