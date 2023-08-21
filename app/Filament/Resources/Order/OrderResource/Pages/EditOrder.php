<?php

namespace App\Filament\Resources\Order\OrderResource\Pages;

use App\Filament\Resources\Order\OrderResource;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = ['permission:Edit Confirmed Bookings'];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['orderable_id']   = auth()->id();
        $data['orderable_type'] = User::class;
        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
