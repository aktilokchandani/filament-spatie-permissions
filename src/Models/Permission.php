<?php

namespace WhiteDev\FilamentPermissions\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public static function defaultPermissions(): array
    {
        return ['view', 'create', 'update', 'delete'];
    }
}