<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OpenAIQuoteController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLogin;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegister;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/my-dashboard', [DashboardController::class, 'updateProfile'])->name('dashboard.update');
    Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel-request');
    Route::get('/my-dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/subscribe', [SubscriptionController::class, 'show'])->name('subscription.page');
    Route::post('/create-checkout-session', [SubscriptionController::class, 'createCheckoutSession'])->name('subscription.checkout');
    Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscribe/cancel', fn() => view('frontend.subscription.cancel'))->name('subscription.cancel');
});
Route::post('/email-subscribe', [SubscriberController::class, 'subscribe'])->name('subscribe');
Route::get('/quote/{uuid}', [OpenAIQuoteController::class, 'show'])->name('quotes.show');
Route::get('/generate-quote/{user}', [OpenAIQuoteController::class, 'generate']);

Route::get('/', function () {
    return view('frontend.home');
});

Route::redirect('/home', '/', 301);

Route::get('/vibe-login', [UserLogin::class, 'showLoginForm'])->name('user.login');
Route::post('/vibe-login', [UserLogin::class, 'login']);

Route::get('/join-vibe', [UserRegister::class, 'showRegistrationForm'])->name('user.register');
Route::post('/join-vibe', [UserRegister::class, 'register']);


// Verification Notice
Route::get('/email/verify', function () {
    return view('frontend.auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle Link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend Link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/landing1', function () {
    return view('holding.landing1');
});
Route::get('/landing2', function () {
    return view('holding.landing2');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role !== 'admin') {
        return redirect()->route('user.dashboard');
    }

    return view('dashboard'); // your admin dashboard view
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
