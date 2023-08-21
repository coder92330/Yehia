<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewMessage extends Mailable
{
    use Queueable, SerializesModels;

    private mixed $data;

    public function __construct($message, $from)
    {
        $this->data = [
            'subject' => __('messages.chat.new_message_subject'),
            'body'    => __('messages.chat.new_message_body', ['from' => $from, 'message' => $message]),
        ];
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.send-mail',
            with: ['data' => $this->data],
        );
    }
}
