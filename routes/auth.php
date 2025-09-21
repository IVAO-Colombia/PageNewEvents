<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SsoIvao;


Route::middleware('guest')->group(function () {
    Route::get('/login', [SsoIvao::class, 'redirectToIVAO'])
        ->name('login');
});


Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');
