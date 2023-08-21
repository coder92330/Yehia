<?php

namespace App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\Pages;

use App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditConfirmedOrder extends EditRecord
{
    use ContextualPage;

    protected static string $resource = ConfirmedOrderResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
