<?php

namespace App\Filament\Resources\Language\LanguageResource\Pages;

use App\Filament\Resources\Language\LanguageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLanguages extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = LanguageResource::class;

    protected static string | array $middlewares = ['permission:List Languages'];

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->orderBy('name')->latest();
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
