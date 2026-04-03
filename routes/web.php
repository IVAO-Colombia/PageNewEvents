<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\SsoIvao;
use App\Models\Event;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [SsoIvao::class, 'redirectToIVAO']);
Route::get('/auth/callback', [SsoIvao::class, 'handleCallback'])->name('ivao.callback');

Route::get('/cookies-policy', function () {
    return view('cookiespolicity');
})->name('cookies.policy');


// Prefix event routes with 'events' and group them together
Route::prefix('events')->group(function () {

    // Route to list all events
    Route::get('/', function () {
        return view('events');
    })->name('events.list');

    // Route to show details of a specific event using route model binding
    Route::get('/{event:slug}', function (Event $event) {
        return view('detailsevent', ['event' => $event]);
    })->middleware(['auth', 'verified'])
        ->name('event.details');

    // Route to show pilot booking for a specific event using route model binding
    Route::get('/{event:slug}/pilot/booking', function (Event $event) {
        return view('searchbookings', ['event' => $event]);
    })->middleware(['auth', 'verified'])
        ->name('search.bookings');

    Route::get('/{event:slug}/atc/booking', function (Event $event) {
        return view('bookingcontroller', ['event' => $event]);
    })->middleware(['auth', 'verified'])
        ->name('bookings.controller');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');





Route::get('detailsBooking/{hash}', function ($hash) {
    $id = \Illuminate\Support\Facades\Route::deobfuscateId($hash);
    $route = \App\Models\Route::findOrFail($id);
    return view('detailsbooking', compact('route'));
})
    ->middleware(['auth', 'verified'])
    ->name('details.booking');



Route::get('/myreservation', function () {
    return view('reservation');
})
    ->name('reservation')
    ->middleware(['auth', 'verified']);

Route::get('/atc', function () {
    return view('controller');
})
    ->middleware(['auth', 'verified'])
    ->name('controller');

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

require __DIR__ . '/auth.php';
