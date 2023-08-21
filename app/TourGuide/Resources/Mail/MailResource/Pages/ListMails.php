<?php

namespace App\TourGuide\Resources\Mail\MailResource\Pages;

use App\Models\Tourguide;
use App\TourGuide\Resources\Mail\MailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListMails extends ListRecords
{
    use ContextualPage;

    protected static string $resource = MailResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where([
            'mailable_id' => auth('tourguide')->id(),
            'mailable_type' => Tourguide::class,
        ]);
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
