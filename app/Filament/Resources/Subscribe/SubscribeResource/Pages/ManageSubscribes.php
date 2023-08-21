<?php

namespace App\Filament\Resources\Subscribe\SubscribeResource\Pages;

use App\Filament\Resources\Subscribe\SubscribeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubscribes extends ManageRecords
{
    protected static string $resource = SubscribeResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
