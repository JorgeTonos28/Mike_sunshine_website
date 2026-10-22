<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class StudioMessage extends Mailable
{
    public function __construct(public string $messageTitle, public string $panelUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->messageTitle);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.studio-message');
    }
}
