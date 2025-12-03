<?php

namespace WhiteDev\FilamentPermissions\Resources;

use WhiteDev\FilamentPermissions\Models\Role;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\ListRoles;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\CreateRole;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\EditRole;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-s-shield-check';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('guard_name')
                ->options(array_combine(array_keys(config('auth.guards')), array_keys(config('auth.guards'))))
                ->default(config('auth.defaults.guard'))
                ->required(),
            Forms\Components\MultiSelect::make('permissions')
                ->relationship('permissions', 'name'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('permissions.name')->label('Permissions')->limit(50),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
