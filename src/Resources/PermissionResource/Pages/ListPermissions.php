<?php

namespace WhiteDev\FilamentPermissions\Resources\PermissionResource\Pages;

use WhiteDev\FilamentPermissions\Resources\PermissionResource\Pages;
use WhiteDev\FilamentPermissions\Models\Permission;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-s-cog';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->size('sm'),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->size('sm'),
                TextColumn::make('guard_name')
                    ->label('Guard Name')
                    ->sortable()
                    ->size('sm'),
            ])
            ->filters([])
            ->actions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
        ];
    }
}