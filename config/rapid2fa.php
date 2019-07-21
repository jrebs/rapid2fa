<?php

return [
    /**
     * If true, Rapid2FA will not create the /login GET route. You might
     * do this if you're replacing the standard login form and want to do
     * more work in the constructor before your custom view is invoked.
     *
     * If it falls back on Auth routes, then this will translate to the
     * App\Http\Controllers\Auth\LoginController::showLoginForm method.
     *
     * @var bool
     */
    'app_login_form' => env('RAPID2FA_APP_LOGIN_FORM', false),

    /**
     * Same as app_login_form above, but will cause the POST /login route
     * to be left to the application.
     *
     * If it falls back on Auth routes, then this will translate to the
     * App\Http\Controllers\Auth\LoginController::login method.
     *
     * @var bool
     */
    'app_login_post' => env('RAPID2FA_APP_LOGIN_POST', false),


    /**
     * Text flashed to the user when two-factor confirmation fails, also
     * as a validation error message on the two-factor login form.
     *
     * @var string
     */
    'failed_text' => env('RAPID2FA_STR_FAILED', 'Two-factor verification failed'),

    /**
     * Text flashed to use when two-factor is enabled.
     *
     * @var string
     */
    'enabled_text' => env('RAPID2FA_STR_ENABLED', 'Two-factor enabled'),

    /**
     * Text flashed to the user when two-factor is disabled.
     *
     * @var string
     */
    'disabled_text' => env('RAPID2FA_STR_DISABLED', 'Two-factor disabled'),

    /**
     * Text shown when a user gets redirected to the rapid2fa.enable route
     * by the require2fa middleware.
     *
     * @var string
     */
    'denied_text' => env('RAPID2FA_STR_DENIED',
        'The requested resource requires two-factor authentication'),
];
