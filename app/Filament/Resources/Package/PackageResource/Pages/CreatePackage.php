<?php

namespace App\Filament\Resources\Package\PackageResource\Pages;

use App\Filament\Resources\Package\PackageResource;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PackageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['start_at'] = now();
        $data['end_at'] = $data['duration_type'] === 'day'
            ? $data['start_at']->addDays($data['duration'])
            : ($data['duration_type'] === 'month'
                ? $data['start_at']->addMonths($data['duration'])
                : $data['start_at']->addYears($data['duration']));

        return $data;
    }

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
