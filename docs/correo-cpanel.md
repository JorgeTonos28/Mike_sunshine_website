# Activar orders@mikesunshine.com en producción

La web envía avisos mediante el SMTP del buzón creado en cPanel. Puedes leerlos y responder desde el webmail de esa misma cuenta. No hace falta integrar IMAP ni insertar el webmail dentro de la web pública.

Según los datos de «Connect Devices» suministrados: servidor `mail.mikesunshine.com`, usuario `orders@mikesunshine.com`, SMTP 465 con SSL/TLS y autenticación. La contraseña es la del buzón, no la de cPanel ni una contraseña de aplicación de Google.

## Configuración en el servidor

Después de actualizar el despliegue, modifica el `.env` de producción:

```dotenv
APP_URL=https://mikesunshine.com
MAIL_MAILER=cpanel
MAIL_HOST=mail.mikesunshine.com
MAIL_USERNAME=orders@mikesunshine.com
MAIL_PASSWORD="CONTRASENA_REAL_DEL_BUZON"
MAIL_FROM_ADDRESS=orders@mikesunshine.com
MAIL_FROM_NAME="Mike Sunshine"
STUDIO_EMAIL=orders@mikesunshine.com
```

El perfil `cpanel` fija SMTPS en puerto 465, exige TLS y conserva la validación de certificados. No utiliza `MAIL_URL`, `MAIL_PORT` ni `MAIL_SCHEME`; no necesita credenciales Gmail. Conserva `APP_KEY`, base de datos y demás variables existentes. La contraseña se introduce sólo en el servidor; nunca se incluye en Git ni en los datos públicos del panel.

El contacto público puede seguir siendo `mikedasunshine@gmail.com`. `STUDIO_EMAIL` decide dónde llegan los avisos de pedidos y consultas, con prioridad sobre el correo visible en la web. Las recuperaciones de contraseña usan el mismo remitente SMTP.

Aplica la configuración, desde la raíz de Laravel:

```bash
php artisan config:cache
php artisan queue:restart
php artisan studio:mail-test
php artisan studio:mail-test --send
```

El primer test sólo diagnostica. `--send` envía una prueba a `orders@mikesunshine.com`; comprueba Recibidos y Spam desde el webmail. También puedes usar `php artisan studio:mail-test TU_CORREO --send` para probar un destinatario tuyo externo. El comando confirma que SMTP aceptó el mensaje; la llegada se comprueba en la bandeja.

## Activar la cola en cPanel

Los pedidos y consultas se guardan primero y sus avisos se procesan en cola. Si el hosting permite un proceso permanente, ejecuta bajo su supervisor:

```bash
php artisan queue:work database --tries=5 --timeout=30
```

En hosting compartido sin supervisor, configura un Cron Job de cPanel cada minuto. Sustituye las rutas por las reales de tu instalación y usa el mismo PHP 8.3+ de la web:

```cron
* * * * * cd /RUTA/AL/PROYECTO && /RUTA/AL/PHP artisan queue:work database --stop-when-empty --max-time=50 --tries=5 --timeout=30 >> storage/logs/queue-worker.log 2>&1
```

En el formulario de cPanel selecciona «Una vez por minuto» y pega únicamente el comando a partir de `cd`, sin los cinco asteriscos. Ese worker termina al vaciar la cola; el siguiente minuto recoge avisos nuevos y reintentos disponibles. Cada proceso carga el `.env` y la caché actuales.

Después, envía una solicitud y una consulta desde la web. Comprueba su registro en el panel y su aviso en el webmail. Las solicitudes previas marcadas como enviadas con el transporte `log` no se vuelven a enviar automáticamente; crea una prueba nueva. Los avisos fallidos admiten reintento tras corregir SMTP.

## Revisiones si falla la entrega

- Verifica que la contraseña corresponde exactamente al buzón y que éste admite acceso desde webmail.
- Comprueba salida hacia `mail.mikesunshine.com:465` y un certificado válido para ese nombre. Si el hosting indica otro hostname certificado en «Connect Devices», usa ese hostname en `MAIL_HOST`; conserva la verificación TLS.
- En cPanel → Email Deliverability, revisa que SPF y DKIM estén válidos para el dominio. Revisa también Spam y los registros de entrega del hosting.
- Si el test directo llega pero los pedidos no, comprueba el worker o cron, `STUDIO_EMAIL` y los trabajos fallidos con `php artisan queue:failed`.
- Para volver a intentar un trabajo fallido concreto: `php artisan queue:retry ID`, o el botón del pedido; elige una sola vía para ese aviso.

No se han introducido credenciales ni realizado envíos externos durante el desarrollo. La entrega real queda lista para comprobarse después de completar el `.env` del servidor.
