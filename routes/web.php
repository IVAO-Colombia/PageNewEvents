<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\SsoIvao;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [SsoIvao::class, 'redirectToIVAO']);
Route::get('/auth/callback', [SsoIvao::class, 'handleCallback'])->name('ivao.callback');

Route::get('/cookies-policy', function () {
    return view('cookiespolicity');
})->name('cookies.policy');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});


/** Language Switcher */
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session()->put('locale', $locale);
        app()->setLocale($locale);
        logger('Language switched to: ' . $locale);
    }
    return redirect()->back();
})->name('language.switch');

require __DIR__.'/auth.php';
