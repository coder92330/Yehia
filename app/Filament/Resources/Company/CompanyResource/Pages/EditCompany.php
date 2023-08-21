<?php

namespace App\Filament\Resources\Company\CompanyResource\Pages;

use App\Filament\Resources\Company\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CompanyResource::class;

    protected static string | array $middlewares = ['permission:Edit Companies'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
