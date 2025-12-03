<?php

namespace WhiteDev\FilamentPermissions;

use Illuminate\Support\ServiceProvider;
use WhiteDev\FilamentPermissions\Commands\GeneratePermissions;
use Filament\Facades\Filament;
use WhiteDev\FilamentPermissions\Filament\FilamentPermissionsPlugin;

class FilamentPermissionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // publish config
        $this->publishes([
            __DIR__.'/../config/filament-permissions.php' => config_path('filament-permissions.php'),
        ], 'config');

        // register commands
        if ($this->app->runningInConsole()) {
            $this->commands([GeneratePermissions::class]);
        }

        // Auto-attach plugin to all panels
        Filament::serving(function () {
            if (Filament::getCurrentPanel()) {
                Filament::getCurrentPanel()->plugin(new FilamentPermissionsPlugin());
            }
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/filament-permissions.php',
            'filament-permissions'
        );
    }
}
