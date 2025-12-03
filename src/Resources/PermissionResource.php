<?php

namespace WhiteDev\FilamentPermissions\Resources;

use WhiteDev\FilamentPermissions\Models\Permission;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables;
use WhiteDev\FilamentPermissions\Resources\Pages\Permissions\ListPermissions;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-s-key';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // You can add form fields here if needed
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('guard_name')->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
        ];
    }
}
