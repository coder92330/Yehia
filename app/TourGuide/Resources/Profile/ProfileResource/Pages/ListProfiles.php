<?php

namespace App\Tourguide\Resources\Profile\ProfileResource\Pages;

use App\Tourguide\Resources\Profile\ProfileResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListProfiles extends ListRecords
{
    use ContextualPage, ListRecords\Concerns\Translatable;

    protected static string $resource = ProfileResource::class;

    protected static string $view = 'filament.pages.tourguide.view-profile';

    protected ?string $heading = '';

    protected function getViewData(): array
    {
        return [
            'record' => auth('tourguide')->user(),
        ];
    }

    public function editPortfolio()
    {
        return redirect()->route('tour-guide.resources.profile.edit', auth('tourguide')->id());
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
