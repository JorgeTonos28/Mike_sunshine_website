<?php

namespace Tests\Feature;

use App\Mail\StudioMailTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudioMailCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'mail.default' => 'gmail',
            'mail.from.address' => 'studio@gmail.com',
            'mail.mailers.gmail.username' => 'studio@gmail.com',
            'mail.mailers.gmail.password' => 'not-a-real-secret',
            'studio.email' => 'studio@gmail.com',
        ]);
    }

    public function test_diagnostics_do_not_send_or_disclose_the_password(): void
    {
        Mail::fake();
        $this->assertSame(0, Artisan::call('studio:mail-test'));
        $this->assertStringContainsString('sin conectar al servidor', Artisan::output());
        $this->assertStringNotContainsString('not-a-real-secret', Artisan::output());
        Mail::assertNothingSent();
    }

    public function test_explicit_send_uses_the_requested_recipient_without_creating_customer_records(): void
    {
        Mail::fake();
        $this->artisan('studio:mail-test', ['recipient' => 'review@gmail.com', '--send' => true])->assertSuccessful();
        Mail::assertSent(StudioMailTest::class, fn ($mail) => $mail->hasTo('review@gmail.com') && str_contains($mail->render(), url('/admin')));
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('inquiries', 0);
        $this->assertDatabaseCount('jobs', 0);
    }

    public function test_log_mode_cannot_claim_a_real_send(): void
    {
        config(['mail.default' => 'log']);
        Mail::fake();
        $this->artisan('studio:mail-test', ['--send' => true])->expectsOutputToContain('no entrega correos reales')->assertFailed();
        Mail::assertNothingSent();
    }

    public function test_missing_credentials_wrong_sender_and_demo_recipient_are_rejected_before_sending(): void
    {
        Mail::fake();
        foreach ([
            ['mail.mailers.gmail.password' => null],
            ['mail.from.address' => 'other@gmail.com'],
            ['studio.email' => 'hola@mikesunshine.example'],
            ['studio.email' => 'invalid'],
        ] as $configuration) {
            $previous = collect($configuration)->mapWithKeys(fn ($value, $key) => [$key => config($key)])->all();
            config($configuration);
            $this->artisan('studio:mail-test', ['--send' => true])->assertFailed();
            config($previous);
        }
        Mail::assertNothingSent();
    }

    public function test_transport_failure_returns_an_error_without_exposing_authentication_details(): void
    {
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('AUTH not-a-real-secret'));
        $this->assertSame(1, Artisan::call('studio:mail-test', ['--send' => true]));
        $this->assertStringContainsString('No se pudo enviar', Artisan::output());
        $this->assertStringNotContainsString('not-a-real-secret', Artisan::output());
    }
}
