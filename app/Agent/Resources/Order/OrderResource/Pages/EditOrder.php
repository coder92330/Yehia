<?php

namespace App\Agent\Resources\Order\OrderResource\Pages;

use App\Agent\Resources\Order\OrderResource;
use App\Models\Agent;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditOrder extends EditRecord
{
    use ContextualPage;

    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = 'permission:Edit Confirmed Bookings';

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['orderable_id']   = auth('agent')->id();
        $data['orderable_type'] = Agent::class;
        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make()->visible(fn() => auth('agent')->user()->hasPermissionTo('View Ordered Bookings')),
            Actions\DeleteAction::make(),
        ];
    }
}
