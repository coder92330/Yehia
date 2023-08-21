<?php

namespace App\Filament\Resources\Mail\MailResource\Pages;

use App\Filament\Resources\Mail\MailResource;
use App\Mail\SendMail;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateMail extends CreateRecord
{
    protected static string $resource = MailResource::class;

    protected static string | array $middlewares = ['permission:Create Mails'];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mailable_id']   = auth()->id();
        $data['mailable_type'] = User::class;
        $mail                  = Mail::to($data['to'])->send(new SendMail($data));
        $data['is_mail_sent']  = (bool) $mail;
        $data['status']        = $mail ? 'sent' : 'failed';
        return $data;
    }
}
