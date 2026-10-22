<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class StudioMailTest extends Mailable
{
    public function __construct(public string $panelUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Prueba de correo · Mike Sunshine');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.studio-test');
    }
}
