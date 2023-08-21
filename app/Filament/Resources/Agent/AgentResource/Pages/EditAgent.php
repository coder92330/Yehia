<?php

namespace App\Filament\Resources\Agent\AgentResource\Pages;

use App\Filament\Resources\Agent\AgentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditAgent extends EditRecord
{
    protected static string $resource = AgentResource::class;

    protected static ?string $title = 'Edit Company Admin';

    protected static string | array $middlewares = 'permission:Edit Company Admins';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (in_array($data['email_verified_at'], [null, false, '0', ''], true)) {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
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
