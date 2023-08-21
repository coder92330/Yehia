<?php

namespace App\Filament\Resources\LandingPage\LandingPageResource\Pages;

use App\Filament\Resources\LandingPage\LandingPageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLandingPages extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = LandingPageResource::class;

    protected static string | array $middlewares = ['permission:List Home Page'];

    protected static ?string $slug = 'landing-page';

    protected static ?string $title = 'Home Page';

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
