<?php

namespace App\Filament\Resources\TourguideSubmitForm\TourguideSubmitFormResource\Pages;

use App\Filament\Resources\TourguideSubmitForm\TourguideSubmitFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourguideSubmitForm extends EditRecord
{
    protected static string $resource = TourguideSubmitFormResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
