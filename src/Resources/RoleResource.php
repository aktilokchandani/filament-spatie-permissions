<?php

namespace WhiteDev\FilamentPermissions\Resources;

use App\Filament\Resources\RoleResource\Pages;
use WhiteDev\FilamentPermissions\Models\Role;
use WhiteDev\FilamentPermissions\Models\Permission;
use Closure;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms;
use Filament\Resources\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Arr;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-s-cog';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make()->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255)
                        ->unique(
                            ignorable: fn (?Role $record) => $record,
                            callback: function (Unique $rule, callable $get) {
                                $guardName = $get('guard_name') ?? config('auth.defaults.guard');
                                return $rule->where('guard_name', $guardName);
                            }
                        ),
                    Forms\Components\Select::make('guard_name')
                        ->label('Guard Name')
                        ->nullable()
                        ->options(fn() => array_combine(array_keys(config('auth.guards', [])), array_keys(config('auth.guards', []))))
                        ->default(config('auth.defaults.guard')),
                    Forms\Components\Toggle::make('select_all')
                        ->label('Select All')
                        ->onIcon('heroicon-s-shield-check')
                        ->offIcon('heroicon-s-shield-exclamation')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            foreach (Role::getEntities() as $entity) {
                                $set($entity, $state);
                                foreach (Role::getPermissions() as $perm) {
                                    $set($entity . '_' . $perm, $state);
                                }
                            }
                        })
                ])
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->sortable()->size('sm'),
            TextColumn::make('name')->sortable()->size('sm'),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}