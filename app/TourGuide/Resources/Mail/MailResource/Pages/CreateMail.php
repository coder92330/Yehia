<?php

namespace App\TourGuide\Resources\Mail\MailResource\Pages;

use App\Models\Tourguide;
use App\TourGuide\Resources\Mail\MailResource;
use App\Mail\SendMail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class CreateMail extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = MailResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mailable_id']   = auth('tourguide')->id();
        $data['mailable_type'] = Tourguide::class;
        $mail                  = Mail::to($data['to'])->send(new SendMail($data));
        $data['is_mail_sent']  = (bool) $mail;
        $data['status']        = $mail ? 'sent' : 'failed';
        return $data;
    }
}
