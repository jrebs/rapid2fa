<?php

Route::get('/rapid2fa/enable', [
    \Jrebs\Rapid2FA\Http\Controllers\Rapid2FAController::class,
    'enableTwoFactor'
])->name('rapid2fa.enable');

Route::get('/rapid2fa/disable', [
    \Jrebs\Rapid2FA\Http\Controllers\Rapid2FAController::class,
    'disableTwoFactor'
])->name('rapid2fa.disable');

Route::post('/rapid2fa/confirm', [
    \Jrebs\Rapid2FA\Http\Controllers\Rapid2FAController::class,
    'confirmTwoFactor'
])->name('rapid2fa.confirm');

if (!config('rapid2fa.app_login_form')) {
    Route::get('/login', [
        \Jrebs\Rapid2FA\Http\Controllers\TwoFactorLoginController::class,
        'showLoginForm'
    ])->middleware(['web', 'guest'])->name('login');
}

if (!config('rapid2fa.app_login_post')) {
    // This route needs to be injected before the Auth routes to function.
    // See Jrebs\Rapid2FA\Providers\Rapid2FAServiceProvider::boot().
    Route::post('/login', [
        \Jrebs\Rapid2FA\Http\Controllers\TwoFactorLoginController::class,
        'login'
    ])->middleware(['web', 'guest']);
}
