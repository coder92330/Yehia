<?php

namespace App\Filament\Resources\Mail\MailResource\Pages;

use App\Filament\Resources\Mail\MailResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMails extends ListRecords
{
    protected static string $resource = MailResource::class;

    protected static string | array $middlewares = ['permission:List Mails'];

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
