<?php

namespace Jrebs\Rapid2FA\Providers;

use Jrebs\Rapid2FA\Console\Commands\ResetCommand;
use Jrebs\Rapid2FA\Http\Middleware\Require2FA;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class Rapid2FAServiceProvider extends ServiceProvider
{
     /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // It would be more appropriate to use $this->loadRoutesFrom() here,
        // but we need to create the login routes before Auth::routes() does.
        $this->app->booted(function () {
            include __DIR__.'/../../routes/web.php';
        });

        $this->loadMigrationsFrom(
            __DIR__.'/../../database/migrations'
        );

        $this->loadViewsFrom(
            __DIR__.'/../../resources/views',
            'rapid2fa'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/rapid2fa.php',
            'rapid2fa'
        );

        $this->publishes([
            __DIR__.'/../../config/rapid2fa.php' => config_path('rapid2fa.php'),
        ]);

        Route::aliasMiddleware('require2fa', Require2FA::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ResetCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
