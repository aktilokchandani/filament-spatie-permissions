<?php

namespace WhiteDev\FilamentPermissions\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use WhiteDev\FilamentPermissions\Resources\RoleResource;
use WhiteDev\FilamentPermissions\Resources\PermissionResource;

class FilamentPermissionsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'whitedev-filament-permissions';
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
