<?php

namespace App\Providers;

use App\Models\Torneo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            if (! $view->offsetExists('torneo')) {
                try {
                    $view->with('torneo', Torneo::actual());
                } catch (\Throwable) {
                    $view->with('torneo', null);
                }
            }
        });
    }
}
