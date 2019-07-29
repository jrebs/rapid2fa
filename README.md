# Rapid2FA Package for Laravel
Wanting to learn how packaging for Laravel works and after integrating
two-factor authentication for a project at work, I made a little weekend
project of building this zero-effort two-factor package for apps using
Laravel with the core auth system.

Strictly speaking, I've more or less accomplished my goal of a zero setup
install. To make the feature useful to your users, of course, you need to
put an element somewhere, but I've provided a drop-in blade fragment you
can copy or use as a guide to make your own.

## Requirements
* `php >= 7.2`
* `laravel/framework >= 5.8`
* `google2fa-laravel`

This package is intended for use in apps using basic Laravel Auth support
and could pose problems alongside other packages or app customizations which
modify the normal handling of the `App\Http\Controllers\Auth\LoginController`
methods.


##  Installation
All of the migrations, routes and views are sourced at runtime dynamically
so no publishing is required, just install and run the migration(s).
```
composer require jrebs/rapid2fa
php artisan migrate
```
You can override all config settings with environmental vars, but if you
want to override the default values used by the config you can publish the
config file to your application's config folder and then modify.
```sh
php artisan vendor:publish --provider=Jrebs\\Rapid2FA\\Providers\\Rapid2FAServiceProvider
```
It's not required, but for safety, I recommend adding `google2fa_secret` to
your `App\User::$hidden` array. This will tell Eloquent not to include this
field when serializing objects, such as in the case of JSON responses.

## Configuration
You can define environmental variables to prevent this package from
overloading your application's `/login` routes as well as define custom
strings to use for validation errors and other small feedback responses.

config|env|type|effect
---|---|---|---
`rapid2fa.app_login_form`|`RAPID2FA_APP_LOGIN_FORM`|`bool`|Set true to allow the app to route the login form render
`rapid2fa.app_login_post`|`RAPID2FA_APP_LOGIN_POST`|`bool`|Set true to allow the app to route the login handler
`rapid2fa.failed_text`|`RAPID2FA_STR_FAILED`|`string`|Overrides the default validation message returned on two-factor failure
`rapid2fa.enabled_text`|`RAPID2FA_STR_ENABLED`|`string`|Override the default two-factor enabled notice
`rapid2fa.disabled_text`|`RAPID2FA_STR_ENABLED`|`string`|Override the default two-factor disabled notice
`rapid2fa.denied_text`|`RAPID2FA_STR_DENIED`|`string`|Override the default message when a user redirected because of `require2fa` middleware

## Usage
It's ready to use. All you need is to offer your users a way to enable or
disable two-factor. Either include this fragment into a view template or
come up with your own display method.
```php
@include('rapid2fa::toggle')
```

## Middleware
A simple middleware layer is available so that you can require a user to be
using two-factor authentication to be able to access particular routes.
```php
Route::middleware(['requires2fa'])->get('/personal', function () {
    // this stuff is extra secret!
});
```

## Customization
This package provides a login form view and a basic view for showing QR codes
to enable two-factor. To modify these views for your application, copy to
`resources/views/vendor/rapid2fa` and then season to taste.

## TODO
* Make configurable text strings translatable
* Possibly refactor to downgrade dependence from `google2fa-laravel` to just
`google2fa`.

## Thanks
This package is merely a connector for a bunch of pre-existing functionality
which was graciously made available to us all. Thanks are due to many, but
especially to Taylor Otwell for Laravel and Antonio Ribeiro for Google2FA.
