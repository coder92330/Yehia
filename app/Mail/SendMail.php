<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private mixed $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.send-mail',
            with: ['data' => $this->data],
        );
    }
}
