<?php

namespace App\Agent\Resources\Mail\MailResource\Pages;

use App\Agent\Resources\Mail\MailResource;
use App\Models\Agent;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ListMails extends ListRecords
{
    use ContextualPage;

    protected static string $resource = MailResource::class;

    protected static string | array $middlewares = 'permission:List Mails';

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label(__('filament::resources/pages/create-record.form.actions.cancel.label'))
            ->requiresConfirmation()
            ->action(fn () => $this->redirect($this->previousUrl ?? static::getResource()::getUrl()))
            ->modalHeading('Discard changes?')
            ->modalSubheading('Are you sure you want to Discard changes? Any changes you have made so far will not be saved.')
            ->modalButton('Discard changes')
            ->color('secondary');
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where([
            'mailable_id' => auth('agent')->id(),
            'mailable_type' => Agent::class,
        ]);
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(fn() => auth('agent')->user()->hasPermissionTo('Create Mails')),
        ];
    }
}
