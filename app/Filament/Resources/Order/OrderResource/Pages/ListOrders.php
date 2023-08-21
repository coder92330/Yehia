<?php

namespace App\Filament\Resources\Order\OrderResource\Pages;

use App\Filament\Resources\Order\OrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = ['permission:List Confirmed Bookings'];

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
