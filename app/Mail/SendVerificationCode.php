<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    private string $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->markdown('emails.send-verification-code')->with(['code' => $this->code]);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Send Verification Code',
        );
    }

    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }
}
