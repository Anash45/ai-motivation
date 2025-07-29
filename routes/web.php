<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;use App\Http\Controllers\SubscriberController;

Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');


Route::get('/', function () {
    return view('holding.index');
});
Route::get('/landing1', function () {
    return view('holding.landing1');
});
Route::get('/landing2', function () {
    return view('holding.landing2');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
