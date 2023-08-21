<?php

namespace App\Filament\Resources\Admin\AdminResource\Pages;

use App\Filament\Resources\Admin\AdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

    protected static string|array $middlewares = 'permission:Create Admins';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole('admin');
    }
}
