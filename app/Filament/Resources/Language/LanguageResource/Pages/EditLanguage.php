<?php

namespace App\Filament\Resources\Language\LanguageResource\Pages;

use App\Filament\Resources\Language\LanguageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanguage extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = LanguageResource::class;

    protected static string | array $middlewares = ['permission:Edit Languages'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
