<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\Field;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('booking.index')
        : redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/booking', function () {
        return view('booking.index', ['fields' => Field::where('is_active', true)->get()]);
    })->name('booking.index');

    Route::get('/booking/{field}', function (Field $field) {
        return view('booking.show', ['field' => $field]);
    })->name('booking.show');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('dashboard', function () {
    $nextBooking = auth()->user()->bookings()
        ->with('field')
        ->whereDate('date', '>=', today())
        ->where('status', '!=', 'cancelled')
        ->orderBy('date')
        ->orderBy('start_time')
        ->first();

    return view('dashboard', ['nextBooking' => $nextBooking]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    Route::get('/admin-only-route', fn () => response('OK'));
});

require __DIR__.'/auth.php';
