<?php

namespace App\Filament\Resources\Package\PackageResource\Pages;

use App\Filament\Resources\Package\PackageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackages extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = PackageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
