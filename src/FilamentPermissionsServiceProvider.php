<?php

namespace WhiteDev\FilamentPermissions;

use Illuminate\Support\ServiceProvider;
use WhiteDev\FilamentPermissions\Commands\GeneratePermissions;

class FilamentPermissionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/filament-permissions.php' => config_path('filament-permissions.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([GeneratePermissions::class]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filament-permissions.php', 'filament-permissions');
    }
}