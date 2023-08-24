<?php

namespace App\Filament\Resources\Language\LanguageResource\Pages;

use App\Filament\Resources\Language\LanguageResource;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateLanguage extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = LanguageResource::class;

    protected static string | array $middlewares = ['permission:Create Languages'];

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
