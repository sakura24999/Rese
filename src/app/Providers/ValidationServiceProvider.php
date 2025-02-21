<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // デバッグ用にログを出力
        Log::info('Current locale: ' . app()->getLocale());
        Log::info('Validation file exists: ' . file_exists(resource_path('lang/ja/validation.php')));
    }
}
