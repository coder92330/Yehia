<?php

namespace App\Filament\Resources\State\StateResource\Pages;

use App\Filament\Resources\State\StateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewState extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = StateResource::class;

    protected static string | array $middlewares = ['permission:View States'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
