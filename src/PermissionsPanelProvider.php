<?php

namespace WhiteDev\FilamentPermissions;

use Filament\Panel;
use Filament\PanelProvider;

class PermissionsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('filament-permissions')
            ->path('permissions')
            ->plugin(FilamentPermissionsPlugin::make());
    }
}
