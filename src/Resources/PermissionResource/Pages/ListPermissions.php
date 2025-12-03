<?php

namespace WhiteDev\FilamentPermissions\Resources\PermissionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use WhiteDev\FilamentPermissions\Resources\PermissionResource;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [25, 50, 100];
    }
}
