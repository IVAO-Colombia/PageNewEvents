<?php

namespace App\Providers;

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

        \Illuminate\Support\Facades\Route::macro('obfuscateId', function ($id) {
            $salt = crc32(config('app.key')) % 1000;
            return base64_encode($id + $salt);
        });

        \Illuminate\Support\Facades\Route::macro('deobfuscateId', function ($hash) {
            $salt = crc32(config('app.key')) % 1000;
            return (int)base64_decode($hash) - $salt;
        });
    }
}
