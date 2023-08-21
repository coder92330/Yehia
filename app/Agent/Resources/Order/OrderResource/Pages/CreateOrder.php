<?php

namespace App\Agent\Resources\Order\OrderResource\Pages;

use App\Agent\Resources\Order\OrderResource;
use App\Models\Agent;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreateOrder extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = 'permission:Create Confirmed Bookings';

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->requiresConfirmation()
            ->action(fn () => $this->redirect($this->previousUrl ?? static::getResource()::getUrl()))
            ->modalHeading(__('modal.heading.cancel'))
            ->modalSubheading(__('modal.subheading.cancel'))
            ->modalButton(__('modal.button.cancel'))
            ->color('secondary');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['orderable_id']   = auth('agent')->id();
        $data['orderable_type'] = Agent::class;
        return $data;
    }
}
