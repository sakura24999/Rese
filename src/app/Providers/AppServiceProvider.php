<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

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
        View::composer('*', function ($view) {
            Log::info('View being rendered', [
                'view_name' => $view->getName(),
                'auth_check' => auth()->check(),
                'user_id' => auth()->check() ? auth()->id() : null,
                'path' => request()->path(),
                'url' => request()->url(),
            ]);
        });

        View::addNamespace('mail', resource_path('views/emails'));
    }
}
