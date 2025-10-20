<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Notifications\EmailVerifiedSuccess;

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::get('/terms-and-conditions', function () {
    return view('legal.terms-and-conditions');
})->name('terms');

Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy');

Route::get('/api/documentation', function () {
    return response()->file(public_path('api-documentation.html'));
});

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('/admin/login')->with('success', 'Email already verified!');
    }

    $user->markEmailAsVerified();

    // Send confirmation email to the user
    if (config('mail.enabled', true)) {
        $user->notify(new EmailVerifiedSuccess());
    }

    return redirect('/admin/login')->with('success', 'Email verified successfully! Check your inbox for confirmation. Your account is now awaiting supervisor approval.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
