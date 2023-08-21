<?php

namespace App\Tourguide\Resources\Profile\ProfileResource\Pages;

use App\Tourguide\Resources\Profile\ProfileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditProfile extends EditRecord
{
    use ContextualPage, EditRecord\Concerns\Translatable;

    protected static string $resource = ProfileResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
