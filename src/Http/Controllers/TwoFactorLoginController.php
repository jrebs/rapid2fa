<?php

namespace Jrebs\Rapid2FA\Http\Controllers;

use Crypt;
use Google2FA;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Encryption\DecryptException;

class TwoFactorLoginController extends LoginController
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('rapid2fa::login');
    }

    /**
     * Authenticate with the parent class and then verify two-factor.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $response = parent::login($request);
        if (!auth()->user()->google2fa_secret) {
            return $response;
        }
        if ($this->validateTwoFactor($request)) {
            return $response;
        }
        $this->incrementLoginAttempts($request);
        auth()->logout();

        throw ValidationException::withMessages([
            'rapid2fa' => [config('rapid2fa.failed_text')],
        ]);
    }

    /**
     * Verify the authenticated user's provided two-factor key.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function validateTwoFactor(Request $request): bool
    {
        $code = $request->input('rapid2fa');
        try {
            $secret = Crypt::decrypt(auth()->user()->google2fa_secret);
        } catch (DecryptException $e) {
            // Somehow the user has an invalid secret key saved and we will
            // never be able to recover from this
            auth()->user()->google2fa_secret = null;
            auth()->user()->save();

            return false;
        }

        return ($code && Google2FA::verifyKey($secret, $code));
    }
}
