<?php

namespace App\Agent\Resources\Event\EventResource\Pages;

use App\Agent\Resources\Event\EventResource;
use App\Models\Event;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditEvent extends EditRecord
{
    use ContextualPage, EditRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string | array $middlewares = 'permission:Update Bookings|Edit Bookings';

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
        $data['lat'] = $data['location']['lat'];
        $data['lng'] = $data['location']['lng'];

        return auth('agent')->user()->whereHas('roles', function ($query) {
            $query->where([['name', 'admin'], ['guard_name', 'agent']])
                ->orWhere([['name', 'super_admin'], ['guard_name', 'agent']]);
        })
            ? $data
            : array_merge($data, ['agent_id' => auth('agent')->id()]);
    }

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make()->visible(fn() => auth('agent')->user()->hasPermissionTo('View Bookings')),
            Actions\DeleteAction::make(),
        ];
    }
}
