<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/',[HomeController::class, 'index'])->name('home');

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

    return redirect('/admin/login')->with('success', 'Email verified successfully! Your account is now awaiting supervisor approval. You will be notified once approved.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
