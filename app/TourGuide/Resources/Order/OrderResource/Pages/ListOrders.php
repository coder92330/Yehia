<?php

namespace App\TourGuide\Resources\Order\OrderResource\Pages;

use App\TourGuide\Resources\Order\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->whereHas('tourguides', function (Builder $query) {
                $query->where([['tourguide_id', auth('tourguide')->id()], ['agent_status', '!=', 'approved']]);
            });
    }

    protected function getActions(): array
    {
        return [];
    }
}
