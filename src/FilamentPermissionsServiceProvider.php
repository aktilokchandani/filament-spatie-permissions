<?php

namespace WhiteDev\FilamentPermissions;

use Illuminate\Support\ServiceProvider;

class FilamentPermissionsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings, if needed
    }

    public function boot(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                 \WhiteDev\FilamentPermissions\Commands\GeneratePermissions::class,
            ]);
        }
    }
}
