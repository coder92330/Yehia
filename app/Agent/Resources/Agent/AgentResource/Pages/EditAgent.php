<?php

namespace App\Agent\Resources\Agent\AgentResource\Pages;

use App\Agent\Resources\Agent\AgentResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class EditAgent extends EditRecord
{
    use ContextualPage;

    protected static string $resource = AgentResource::class;

    protected static string|array $middlewares = 'permission:Edit Agents';

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->requiresConfirmation()
            ->action(fn () => $this->redirect($this->previousUrl ?? static::getResource()::getUrl()))
            ->modalHeading(__('modal.cancel.heading'))
            ->modalSubheading(__('modal.cancel.subheading'))
            ->modalButton(__('modal.cancel.button'))
            ->color('secondary');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (in_array($data['email_verified_at'], [null, false, '0', ''], true)) {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
        }

        return $data;
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
