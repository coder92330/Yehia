<?php

namespace App\Filament\Resources\Package\PackageResource\Pages;

use App\Filament\Resources\Package\PackageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackage extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = PackageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ((isset($data['duration_type']) && $data['duration_type'] !== $this->record->duration_type) ||
            (isset($data['duration']) && $data['duration'] !== $this->record->duration)) {
            $data['start_at'] = $this->record->start_at ?? now();
            $data['end_at'] = $data['duration_type'] === 'day'
                ? $this->record->start_at->addDays($data['duration'])
                : ($data['duration_type'] === 'month'
                    ? $this->record->start_at->addMonths($data['duration'])
                    : $this->record->start_at->addYears($data['duration']));
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
