<?php

namespace App\Filament\Resources\Admin\AdminResource\Pages;

use App\Filament\Resources\Admin\AdminResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected static string|array $middlewares = 'permission:Edit Admins';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
