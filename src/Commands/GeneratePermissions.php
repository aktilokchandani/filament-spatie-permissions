<?php

namespace WhiteDev\FilamentPermissions\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class GeneratePermissions extends Command
{
    protected \$signature = 'filament-permissions:generate';
    protected \$description = 'Generate CRUD permissions for all Filament resources';

    public function handle()
    {
        \$resources = Filament::getResources();
        \$actions = config('filament-permissions.permissions', []);

        foreach (\$resources as \$resource) {
            \$name = Str::before(class_basename(\$resource), 'Resource');

            foreach (\$actions as \$action) {
                \$permissionName = strtolower("{\$action}_{\$name}");
                Permission::firstOrCreate(['name' => \$permissionName]);
                \$this->info("Permission created: {\$permissionName}");
            }
        }

        \$this->info('All permissions generated successfully!');
    }
}