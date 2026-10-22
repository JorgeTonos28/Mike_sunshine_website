# Probar el panel y los avisos con Gmail

## Entrar en administración

Desde la carpeta del proyecto, crea tu propia cuenta:

```powershell
php artisan studio:admin tu.correo@gmail.com
```

El comando pide nombre y contraseña de forma interactiva. Usa al menos 12 caracteres, con mayúsculas, minúsculas y números. No hay una contraseña predeterminada ni registro público. Si el correo ya existe, entra con esa cuenta o usa la recuperación de contraseña una vez configurado el correo.

Con el servidor local en marcha, abre [el panel](http://127.0.0.1:8000/admin); te llevará a [iniciar sesión](http://127.0.0.1:8000/login). Si necesitas iniciarlo: `php artisan serve --host=127.0.0.1 --port=8000`.

Puedes probar estas funciones:

- **Información del negocio:** contacto, redes, logo, biografía y retrato del artista. Cambiar el retrato actualiza Sobre mí.
- **Obras:** crea un borrador, añade imágenes y bloques de detalle; define precio y existencias antes de publicarlo.
- **Solicitudes:** abre un pedido para ver datos de entrega, obras solicitadas, espera de disponibilidad, notas, estado y aviso de correo.
- **Consultas:** revisa los mensajes enviados desde Contacto y servicios.
- **Usuarios:** crea administradores o editores. Los editores sólo gestionan el catálogo.

El acceso con formulario funciona sin Google. El botón de Google necesita las credenciales OAuth y el callback configurados en el proyecto; sólo acepta usuarios activos previamente creados. Esa configuración es independiente del envío de correos por Gmail.

## Preparar el Gmail personal

No hace falta un buzón del dominio. Usaremos SMTP de la cuenta personal de Gmail.

1. Activa la verificación en dos pasos en esa cuenta.
2. Abre [Contraseñas de aplicación de Google](https://myaccount.google.com/apppasswords) y crea una para «Mike Sunshine web».
3. Guarda sus 16 caracteres únicamente en el `.env` local o en los secretos del servidor. Cópialos sin los espacios de agrupación. No uses la contraseña normal de Gmail ni la contraseña del panel.

Google exige verificación en dos pasos para las contraseñas de aplicación y puede ocultar esa opción en cuentas con Protección Avanzada, algunas cuentas administradas o configuraciones basadas sólo en llaves de seguridad. Si cambias la contraseña de la cuenta, Google revoca las contraseñas de aplicación y tendrás que crear otra. [Requisitos oficiales de Google](https://support.google.com/accounts/answer/185833?hl=es).

Edita estas variables del `.env` existente, sustituyendo los ejemplos:

```dotenv
APP_URL=http://127.0.0.1:8000
MAIL_MAILER=gmail
MAIL_USERNAME="tu.correo@gmail.com"
MAIL_PASSWORD="CONTRASENA_DE_APLICACION"
MAIL_FROM_ADDRESS="tu.correo@gmail.com"
MAIL_FROM_NAME="Mike Sunshine"
STUDIO_EMAIL="tu.correo@gmail.com"
```

El perfil `gmail` ya fija `smtp.gmail.com`, puerto 587, STARTTLS obligatorio y un timeout de 15 segundos. Mantiene la comprobación de certificados. Los valores genéricos `MAIL_HOST`, `MAIL_PORT`, `MAIL_SCHEME` y `MAIL_URL` no intervienen en ese perfil. El remitente debe ser el mismo Gmail autenticado para este procedimiento. [Configuración SMTP publicada por Google](https://support.google.com/mail/answer/7104828?hl=es).

`STUDIO_EMAIL` recibe los avisos privados de pedidos y consultas y tiene prioridad sobre el correo público de Información del negocio. Si queda vacío, se usa ese correo público. Así puedes mantener un contacto público distinto del Gmail que recibe las solicitudes. Para las primeras pruebas pon tu Gmail; luego cambia las credenciales, el remitente y el destinatario por los de Mike.

Aplica los cambios:

```powershell
php artisan config:clear
php artisan queue:restart
```

Vuelve a iniciar el worker si estaba abierto. Los procesos de larga duración deben cargar la configuración nueva. En producción, después de completar las variables, vuelve a generar la caché con `php artisan config:cache`.

## Probar SMTP por separado

Primero revisa la configuración sin enviar ni abrir una conexión SMTP:

```powershell
php artisan studio:mail-test
```

Mostrará transporte, remitente y destinatario, nunca la contraseña. Detecta correos inválidos, datos de demostración, credenciales ausentes y un remitente distinto de la cuenta Gmail. Con `log` o `array` informa de que no hay entrega real.

Para enviar un único correo real al buzón configurado:

```powershell
php artisan studio:mail-test --send
```

También puedes indicar otro correo tuyo como destinatario de esa prueba:

```powershell
php artisan studio:mail-test tu.otro.correo@gmail.com --send
```

Busca el asunto **Prueba de correo · Mike Sunshine** en Recibidos y Spam. El comando confirma que el servidor aceptó el mensaje; la llegada se verifica en la bandeja. Envía directamente, sin worker, y no crea pedidos, consultas ni trabajos de cola. Usa el mismo transporte y remitente que los avisos del sitio.

## Probar un pedido y una consulta completos

Abre otra terminal en el proyecto y deja este proceso ejecutándose:

```powershell
php artisan queue:work database --tries=5 --timeout=30
```

1. En la web, elige una obra, indica cantidad, añádela al carrito y envía una solicitud con tus datos de prueba y los consentimientos del formulario. También puedes probar una obra sin existencias: mostrará la espera de disponibilidad.
2. Entra en **Solicitudes** del panel y comprueba la nueva referencia y sus datos.
3. Con el worker activo, el aviso pasa de pendiente a enviado y llega al Gmail de `STUDIO_EMAIL`. El mensaje contiene referencia y enlace al panel; los datos del cliente quedan dentro del panel protegido.
4. Envía una consulta desde Contacto o desde el modal de un servicio. Comprueba **Consultas** y el nuevo aviso de correo.

Las solicitudes quedan guardadas aunque falle SMTP. Los envíos se reintentan hasta cinco veces, con esperas de 60, 300, 900 y 3600 segundos. Un aviso marcado como enviado significa que el transporte lo aceptó; revisa Gmail para comprobar la entrega.

Las pruebas hechas anteriormente con `MAIL_MAILER=log` se escribieron en `storage/logs/laravel.log` y pueden figurar como enviadas, aunque no llegaron a un buzón. Después de activar Gmail crea una solicitud nueva para comprobar la entrega real.

## Si algo falla

- **SMTP funciona con el comando pero no llegan avisos de la web:** comprueba que el worker esté activo, que haya cargado el `.env` nuevo y que `STUDIO_EMAIL` sea correcto.
- **Falla autenticación:** revisa cuenta completa, contraseña de aplicación y verificación en dos pasos. Si cambiaste la contraseña principal de Google, genera una contraseña de aplicación nueva.
- **Timeout o conexión rechazada:** el servidor debe poder salir hacia `smtp.gmail.com:587`.
- **Error TLS en Windows/PHP:** configura un archivo CA válido en `openssl.cafile` de `php.ini` y reinicia PHP. Conserva la verificación TLS; no la desactives para resolverlo.
- **Aviso agotado:** revisa `php artisan queue:failed`. Tras corregir la configuración, `php artisan queue:retry ID` reintenta un trabajo fallido concreto. La ficha de un pedido también ofrece reintentar el aviso fallido; elige una sola vía para ese aviso.
- **Gmail no lo muestra:** comprueba Spam, remitente/destinatario y la actividad de seguridad de Google. El comando devuelve error si el servidor rechaza la prueba, sin mostrar detalles de autenticación.

Estado al preparar esta guía: el entorno local usa `MAIL_MAILER=log`; no se han introducido credenciales de Gmail ni enviado mensajes externos. Las pruebas automatizadas simulan el transporte; la entrega real queda lista para verificarse con la cuenta que configures.
