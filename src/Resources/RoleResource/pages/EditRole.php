<?php

namespace WhiteDev\FilamentPermissions\Resources\Pages;

use Filament\Resources\Pages\EditRecord;
use WhiteDev\FilamentPermissions\Resources\RoleResource;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
}
