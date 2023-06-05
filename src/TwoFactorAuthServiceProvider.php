<?php

namespace RaisulHridoy\Laravel2FA;

class TwoFactorAuthServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/config/laravel2fa.php' => config_path('laravel2fa.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel2fa.php', 'laravel2fa'
        );
    }
}
