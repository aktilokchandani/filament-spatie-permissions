<?php

namespace WhiteDev\FilamentPermissions\Resources\RoleResource\Pages;

use WhiteDev\FilamentPermissions\Resources\RoleResource;
use WhiteDev\FilamentPermissions\Models\Permission;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * Selected permissions before create
     *
     * @var array
     */
    public array $permissions = [];

    /**
     * Capture selected permissions before creating the role
     */
    public function beforeCreate(): void
    {
        // Extract permission fields from form data
        $this->permissions = array_keys(array_filter(
            Arr::except($this->data, ['name', 'guard_name', 'select_all'] + RoleResource::getEntities())
        ));
    }

    /**
     * After creating the role, sync permissions
     */
    public function afterCreate(): void
    {
        $permissions = [];
        foreach ($this->permissions as $name) {
            $permissions[] = Permission::findOrCreate($name, $this->record->guard_name);
        }

        $this->record->syncPermissions($permissions);

        // Optional: assign doptor_id from authenticated employee
        if ($employee = auth()->user()?->employee) {
            $this->record->update(['doptor_id' => $employee->doptor->id]);
        }
    }

    /**
     * Filter form data to only include the necessary fields for the Role model
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return Arr::only($data, ['name', 'guard_name']);
    }

    /**
     * Redirect to index after create
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Custom form actions for Filament 3
     */
    protected function getFormActions(): array
    {
        return array_merge(
            [$this->getCreateFormAction()->label('Save')->icon('heroicon-o-plus-circle')->size('sm')],
            static::canCreateAnother() 
                ? [$this->getCreateAnotherFormAction()->label('Save & Add Another Role')->icon('heroicon-o-plus-circle')->size('sm')] 
                : [],
            [$this->getCancelFormAction()->label('Back')->icon('heroicon-o-arrow-left')->size('sm')]
        );
    }
}
