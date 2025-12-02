<?php

namespace WhiteDev\FilamentPermissions\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected static function booted()
    {
        // custom boot logic if needed
    }

    public static function getPermissions(): array
    {
        return ['view', 'viewAny', 'create', 'update', 'delete', 'deleteAny'];
    }
}