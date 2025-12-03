<?php

namespace WhiteDev\FilamentPermissions\Resources;

use WhiteDev\FilamentPermissions\Models\Role;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\ListRoles;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\CreateRole;
use WhiteDev\FilamentPermissions\Resources\RoleResource\Pages\EditRole;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Illuminate\Validation\Rules\Unique;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-s-shield-check';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(255)
                                ->unique(
                                    fn (?Role $record): ?Role => $record,
                                    function (Unique $rule, callable $get): Unique {
                                        $guardName = $get('guard_name') ?? config('auth.defaults.guard');
                                        return $rule->where('guard_name', $guardName);
                                    }
                                ),
                            
                                Forms\Components\Select::make('guard_name')
                                ->label('Guard Name')
                                ->options(array_combine(array_keys(config('auth.guards')), array_keys(config('auth.guards'))))
                                ->default(config('auth.defaults.guard'))
                                ->required(),

                            Forms\Components\Toggle::make('select_all')
                                ->label('Select All')
                                ->reactive()
                                ->afterStateUpdated(function ($set, $get, $state) {
                                    foreach (Role::getEntities() as $entity) {
                                        $set($entity, $state);
                                        foreach (Role::getPermissions() as $perm) {
                                            $set($entity . '_' . $perm, $state);
                                        }
                                    }
                                }),
                        ]),
                ]),

            Forms\Components\Grid::make([
                'sm' => 2,
                'lg' => 3,
                'xl' => 3,
            ])
                ->schema(static::getEntitySchema())
                ->columns([
                    'sm' => 2,
                    'lg' => 2,
                    'xl' => 3,
                ]),
        ]);
    }

    protected static function getEntitySchema(): array
    {
        return collect(Role::getEntities())->map(function (string $entity) {
            return Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Toggle::make($entity)
                        ->label(__($entity))
                        ->reactive()
                        ->afterStateUpdated(function ($set, $get, $state) use ($entity) {
                            foreach (Role::getPermissions() as $perm) {
                                $set($entity . '_' . $perm, $state);
                            }

                            if (!$state) {
                                $set('select_all', false);
                            }

                            static::freshSelectAll($get, $set);
                        }),

                    Forms\Components\Fieldset::make('Permissions')
                        ->label('Permissions')
                        ->columns(2)
                        ->schema(static::getPermissionsSchema($entity)),
                ])
                ->columns(2)
                ->columnSpan(1);
        })->toArray();
    }

    protected static function getPermissionsSchema(string $entity): array
    {
        return collect(Role::getPermissions())->map(function (string $permission) use ($entity) {
            return Forms\Components\Checkbox::make($entity . '_' . $permission)
                ->label(__($permission))
                ->reactive()
                ->afterStateHydrated(function ($set, $get, ?Role $record) use ($entity, $permission) {
                    if (!$record) return;

                    if ($record->checkPermissionTo($entity . '_' . $permission)) {
                        $set($entity . '_' . $permission, true);
                    }

                    if ($record->checkPermissionTo($entity)) {
                        $set($entity, true);
                    } else {
                        $set($entity, false);
                        $set('select_all', false);
                    }

                    static::freshSelectAll($get, $set);
                })
                ->afterStateUpdated(function ($set, $get, $state) use ($entity) {
                    $permissionStates = [];

                    foreach (Role::getPermissions() as $perm) {
                        $permissionStates[] = $get($entity . '_' . $perm);
                    }

                    if (!in_array(false, $permissionStates, true)) {
                        $set($entity, true);
                    }

                    if (in_array(false, $permissionStates, true)) {
                        $set($entity, false);
                        $set('select_all', false);
                    }

                    static::freshSelectAll($get, $set);
                });
        })->toArray();
    }

    protected static function freshSelectAll($get, $set): void
    {
        $allEntities = collect(Role::getEntities())
            ->every(fn ($entity) => (bool) $get($entity));

        $set('select_all', $allEntities);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->size('sm'),
                TextColumn::make('name')->sortable()->searchable()->size('sm'),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->getStateUsing(fn ($record) => $record->permissions->count())
                    ->size('sm'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(), // ðŸ‘ˆ Yahi create button show karega
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }
}
