<?php

namespace App\TourGuide\Resources\Company\CompanyResource\Pages;

use App\Models\Tourguide;
use App\TourGuide\Resources\Company\CompanyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereHas('agents.favourites', function (Builder $query) {
                $query->where([
                    'favouritable_type' => Tourguide::class,
                    'favouritable_id' => auth('tourguide')->id(),
                ]);
            });
    }

    protected function getActions(): array
    {
        return [];
    }
}
