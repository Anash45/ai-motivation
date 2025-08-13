<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TestEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OpenAIQuoteController;
use App\Http\Controllers\TrialReminderController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Frontend\Auth\LoginController as UserLogin;
use App\Http\Controllers\Frontend\Auth\RegisterController as UserRegister;
use App\Http\Controllers\QuoteGenerationController;

Route::get('/server-time', function () {
    return now()->toDateTimeString();
});

Route::get('/send-trial-reminders', [TrialReminderController::class, 'sendTrialEndingReminders']);


Route::get('/run-daily-quotes', function () {
    // Call the controller method directly
    return app(QuoteGenerationController::class)->generate();
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/my-dashboard', [DashboardController::class, 'updateProfile'])->name('dashboard.update');
    Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel-request');
    Route::get('/my-dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);

Route::middleware(['auth'])->group(function () {
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
})->name('home');

Route::get('/terms-and-conditions', function () {
    return view('frontend.tos');
});


Route::get('/privacy-policy', function () {
    return view('frontend.privacy-policy');
});

Route::redirect('/home', '/', 301);

Route::get('/vibe-login', [UserLogin::class, 'showLoginForm'])->name('user.login');
Route::post('/vibe-login', [UserLogin::class, 'login']);

Route::get('/join-vibe', [UserRegister::class, 'showRegistrationForm'])->name('user.register');
Route::post('/join-vibe', [UserRegister::class, 'register']);
Route::get('/test-email', [TestEmailController::class, 'testEmail'])->name('test.email');

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

    return app(AdminDashboardController::class)->index(request());

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
});

Route::get('/admin/quotes', [AdminDashboardController::class, 'quotes'])
    ->middleware(['auth', 'admin'])
    ->name('admin.quotes.index');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
