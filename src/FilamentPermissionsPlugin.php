<?php

namespace WhiteDev\FilamentPermissions;

use Filament\Contracts\Plugin;
use Filament\Panel;
use WhiteDev\FilamentPermissions\Resources\RoleResource;
use WhiteDev\FilamentPermissions\Resources\PermissionResource;

class FilamentPermissionsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-permissions';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RoleResource::class,
            PermissionResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
