<?php

namespace App\Agent\Resources\Agent\AgentResource\Pages;

use App\Agent\Resources\Agent\AgentResource;
use App\Models\Agent;
use App\Models\Style;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;
use Spatie\Permission\Models\Role;

class CreateAgent extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = AgentResource::class;

    protected static string|array $middlewares = 'permission:Create Agents';

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (is_null($data['password']) || $data['password'] === '') {
            unset($data['password'], $data['password_confirmation']);
        } else {
            unset($data['password_confirmation']);
            $data['password'] = Hash::make($data['password']);
        }

        if (is_null($data['email_verified_at']) || $data['email_verified_at'] === '') {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = null;
        } else {
            unset($data['email_verified_at']);
            $data['email_verified_at'] = now();
        }

        $data['company_id'] = auth('agent')->user()->company->id;

        $data['style_id'] = Style::defaultStyleId();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->assignRole(Role::findByName('user', 'agent'));
    }
}
