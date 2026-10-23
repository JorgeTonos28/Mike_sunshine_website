<?php

namespace App\Console\Commands;

use App\Mail\StudioMailTest;
use App\Services\StudioMailbox;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TestStudioMail extends Command
{
    protected $signature = 'studio:mail-test {recipient? : Destinatario de la prueba; por defecto el buzón del estudio} {--send : Enviar un único correo real ahora, sin usar la cola}';

    protected $description = 'Diagnostica el correo sin enviar nada; --send envía una prueba explícita';

    public function handle(StudioMailbox $mailbox): int
    {
        try {
            $recipient = $this->argument('recipient') ?: $mailbox->recipient();
        } catch (\RuntimeException) {
            $this->error('Configura STUDIO_EMAIL o el correo de contacto del panel; también puedes indicar un destinatario al comando.');

            return self::FAILURE;
        }
        $from = (string) config('mail.from.address');
        if (Validator::make(['to' => $recipient, 'from' => $from], ['to' => 'required|email:rfc|max:255', 'from' => 'required|email:rfc|max:255'])->fails()) {
            $this->error('El destinatario y MAIL_FROM_ADDRESS deben ser correos válidos.');

            return self::FAILURE;
        }
        $mailer = (string) config('mail.default');
        $settings = config('mail.mailers.'.$mailer, []);
        $transport = $settings['transport'] ?? null;
        if (! $transport) {
            $this->error('MAIL_MAILER no corresponde a un transporte configurado.');

            return self::FAILURE;
        }
        $this->line('Transporte: '.$mailer.' ('.$transport.')');
        $this->line('Remitente: '.$from);
        $this->line('Destinatario: '.$recipient);

        if (in_array($transport, ['log', 'array'], true)) {
            $this->warn('Este transporte no entrega correos reales. Configura MAIL_MAILER=cpanel o tu transporte SMTP.');

            return $this->option('send') ? self::FAILURE : self::SUCCESS;
        }
        if ($transport === 'smtp' && (empty($settings['username']) || empty($settings['password']))) {
            $this->error('Faltan MAIL_USERNAME o MAIL_PASSWORD. En cPanel usa la contraseña del buzón; en Gmail, una contraseña de aplicación.');

            return self::FAILURE;
        }
        if (in_array($mailer, ['gmail', 'cpanel'], true) && strcasecmp($from, (string) $settings['username']) !== 0) {
            $this->error('Con este perfil, MAIL_FROM_ADDRESS debe coincidir con MAIL_USERNAME.');

            return self::FAILURE;
        }
        foreach ([$recipient, $from] as $address) {
            $domain = strtolower(substr(strrchr($address, '@'), 1));
            if (preg_match('/(?:^|\.)(example|test|invalid|localhost)$/', $domain) || in_array($domain, ['example.com', 'example.org', 'example.net'], true)) {
                $this->error('Sustituye los correos de demostración por direcciones reales antes de enviar.');

                return self::FAILURE;
            }
        }
        if (! $this->option('send')) {
            $this->info('Configuración revisada, sin conectar al servidor. Añade --send para probar la autenticación y el envío.');

            return self::SUCCESS;
        }
        try {
            Mail::to($recipient)->send(new StudioMailTest(url('/admin')));
        } catch (\Throwable) {
            // SMTP exceptions can contain connection/authentication details; never print their raw messages.
            $this->error('No se pudo enviar la prueba. Revisa usuario y contraseña SMTP, el servidor y puerto configurados y los certificados TLS de PHP. No se han impreso credenciales.');

            return self::FAILURE;
        }
        $this->info('El servidor aceptó el mensaje de prueba. Comprueba Recibidos y Spam. No se creó ningún pedido ni consulta.');

        return self::SUCCESS;
    }
}
