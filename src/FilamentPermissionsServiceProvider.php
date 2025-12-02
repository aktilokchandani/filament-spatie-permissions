<?php

namespace WhiteDev\FilamentPermissions;

use Illuminate\Support\ServiceProvider;
use WhiteDev\FilamentPermissions\Commands\GeneratePermissions;
use Filament\Facades\Filament;
use WhiteDev\FilamentPermissions\Resources\RoleResource;
use WhiteDev\FilamentPermissions\Resources\PermissionResource;

class FilamentPermissionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/filament-permissions.php' => config_path('filament-permissions.php'),
        ], 'config');

        // Register command
        if ($this->app->runningInConsole()) {
            $this->commands([GeneratePermissions::class]);
        }

        // **Register Filament Resources**
        Filament::registerResources([
            RoleResource::class,
            PermissionResource::class,
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/filament-permissions.php', 'filament-permissions');
    }
}
