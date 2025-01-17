<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Request::macro('isHtmx', function () {
            return $this->header('HX-Request') === 'true';
        });

        Request::macro('isXhr', function () {
            return $this->ajax() || $this->header('HX-Request') === 'true';
        });
    }
}
