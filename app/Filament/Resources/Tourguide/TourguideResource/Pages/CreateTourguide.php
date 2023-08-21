<?php

namespace App\Filament\Resources\Tourguide\TourguideResource\Pages;

use App\Filament\Resources\Tourguide\TourguideResource;
use App\Models\Setting;
use Filament\Pages\Actions;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTourguide extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = TourguideResource::class;

    protected static string | array $middlewares = ['permission:Create Tourguides'];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (is_null($data['email_verified_at']) || $data['email_verified_at'] === '') {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->settings()->syncWithPivotValues(Setting::all()->pluck("id")->toArray(), ["value" => 1]);
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
