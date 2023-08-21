<?php

namespace App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource\Pages;

use App\Agent\Resources\ConfirmedOrder\ConfirmedOrderResource;
use Filament\Resources\Pages\CreateRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreateConfirmedOrder extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = ConfirmedOrderResource::class;
}
