<?php

namespace Tests\Feature;

use App\Jobs\NotifyStudio;
use App\Mail\StudioMessage;
use App\Models\Inquiry;
use App\Models\StudioSetting;
use App\Services\StudioMailbox;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudioMailboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_private_recipient_overrides_public_demo_contact_for_notifications(): void
    {
        config(['studio.email' => 'mike@example.test']);
        StudioSetting::current()->update(['contact_email' => 'hola@mikesunshine.example']);
        $inquiry = Inquiry::create(['name' => 'Visitante', 'email' => 'visitor@example.test', 'subject' => 'Consulta', 'message' => 'Mensaje privado de prueba', 'privacy_accepted_at' => now()]);
        Mail::fake();

        (new NotifyStudio('inquiry', $inquiry->id))->handle();

        Mail::assertSent(StudioMessage::class, fn ($mail) => $mail->hasTo('mike@example.test') && ! str_contains($mail->render(), 'visitor@example.test'));
        $this->assertSame('sent', $inquiry->fresh()->notification_status);
    }

    public function test_public_contact_is_used_when_no_private_recipient_is_configured(): void
    {
        config(['studio.email' => '  ']);
        StudioSetting::current()->update(['contact_email' => 'public@example.test']);
        $this->assertSame('public@example.test', app(StudioMailbox::class)->recipient());
    }

    public function test_invalid_override_does_not_silently_send_to_another_address(): void
    {
        config(['studio.email' => 'invalid']);
        StudioSetting::current()->update(['contact_email' => 'public@example.test']);
        $this->expectException(\RuntimeException::class);
        app(StudioMailbox::class)->recipient();
    }

    public function test_gmail_transport_requires_tls_and_has_a_bounded_timeout(): void
    {
        $transport = app('mail.manager')->mailer('gmail')->getSymfonyTransport();
        $this->assertTrue($transport->isTlsRequired());
        $this->assertSame(15.0, $transport->getStream()->getTimeout());
        $this->assertSame('smtp.gmail.com', $transport->getStream()->getHost());
        $this->assertSame(587, $transport->getStream()->getPort());
    }

    public function test_cpanel_uses_implicit_tls_on_port_465(): void
    {
        config(['mail.mailers.cpanel.host' => 'mail.mikesunshine.com']);
        $transport = app('mail.manager')->mailer('cpanel')->getSymfonyTransport();
        $this->assertTrue($transport->getStream()->isTLS());
        $this->assertTrue($transport->isTlsRequired());
        $this->assertSame(15.0, $transport->getStream()->getTimeout());
        $this->assertSame('mail.mikesunshine.com', $transport->getStream()->getHost());
        $this->assertSame(465, $transport->getStream()->getPort());
    }
}
