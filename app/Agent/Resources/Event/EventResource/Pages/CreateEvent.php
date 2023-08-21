<?php

namespace App\Agent\Resources\Event\EventResource\Pages;

use App\Agent\Resources\Event\EventResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreateEvent extends CreateRecord
{
    use ContextualPage, Translatable;

    protected static string $resource = EventResource::class;

    protected static string|array $middlewares = 'permission:Create Bookings';

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->requiresConfirmation()
            ->action(fn() => $this->redirect($this->previousUrl ?? static::getResource()::getUrl()))
            ->modalHeading(__('modal.heading.cancel'))
            ->modalSubheading(__('modal.subheading.cancel'))
            ->modalButton(__('modal.button.cancel'))
            ->color('secondary');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['lat'] = $data['location']['lat'];
        $data['lng'] = $data['location']['lng'];

        if ($data['days_type'] !== 'multi') {
            $data['end_at'] = Carbon::parse($data['start_at'])->addDay();
        }

        unset($data['start_time'], $data['end_time'], $data['days_type']);

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
            // ...
        ];
    }
}
