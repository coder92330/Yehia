<?php

namespace App\Agent\Resources\Company\CompanyResource\Pages;

use App\Agent\Resources\Company\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditCompany extends EditRecord
{
    use EditRecord\Concerns\Translatable, ContextualPage;

    protected static string $resource = CompanyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
