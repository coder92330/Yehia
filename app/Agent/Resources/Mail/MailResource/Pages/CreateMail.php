<?php

namespace App\Agent\Resources\Mail\MailResource\Pages;

use App\Agent\Resources\Mail\MailResource;
use App\Mail\SendMail;
use App\Models\Agent;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreateMail extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = MailResource::class;

    protected static string | array $middlewares = 'permission:Create Mails';

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mailable_id']   = auth('agent')->id();
        $data['mailable_type'] = Agent::class;
        $mail                  = Mail::to($data['to'])->send(new SendMail($data));
        $data['is_mail_sent']  = (bool) $mail;
        $data['status']        = $mail ? 'sent' : 'failed';
        return $data;
    }
}
