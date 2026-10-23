# Actualización para el lanzamiento

## Datos confirmados

La información pública procede del cuestionario de Mike y de las confirmaciones de esta conversación:

- Nombre artístico: Mike Sunshine. Responsable: Michael Febles Quezada.
- Contacto público: `mikedasunshine@gmail.com`.
- Teléfono y WhatsApp confirmados: `+1 849-428-8682`.
- Domicilio público: No. 4-A, Edificio B-1, calle Jacinto de la Concha, sector Villa Progreso del Este, Santo Domingo Este, República Dominicana.
- Única red mostrada: [Instagram](https://www.instagram.com/mikesunshinegoat/). Facebook y YouTube quedan vacíos.
- Pinturas ofrecidas como prints de 16 × 20 pulgadas, con firma y marco; esculturas por pedido y cotización. No se han inventado precios ni datos fiscales.
- Buzón privado de órdenes y remitente SMTP: `orders@mikesunshine.com`, según la última decisión del usuario. Es independiente del correo público.

## Aplicar en producción

Después de actualizar `main`, instalar dependencias y compilar los recursos con el procedimiento habitual, ejecuta desde la raíz de Laravel:

```bash
php artisan migrate --force
php artisan studio:launch-install --dry-run
php artisan studio:launch-install --force
```

El último comando aplica una sola vez el lanzamiento `launch-2026-10-23-v1`:

1. Carga los datos públicos de Mike en Información del negocio y elimina los enlaces de muestra de Facebook/YouTube.
2. Sustituye Privacidad, Términos y Cookies por sus textos de lanzamiento, incrementa sus versiones y los marca como publicados/aprobados. Desaparecen el aviso provisional y `noindex`.
3. Conserva en `site_releases.previous_content` los campos de contacto y textos legales anteriores. Ese registro queda en la base de datos y no se comparte como información pública de Inertia.
4. Al repetirse, conserva las ediciones posteriores del panel; no vuelve a aplicar los valores iniciales.

La carga no cambia usuarios, credenciales, pedidos, precios, inventario ni la clave de cifrado. Para cargar el catálogo si aún falta, el comando independiente sigue siendo `php artisan studio:catalog-install --force`.

En el panel, **Información del negocio** permite editar también el nombre legal del responsable. Las tres páginas legales muestran el contacto actual del panel y se editan desde **Páginas legales**. Los textos explican compras por solicitud y confirmación escrita, prints y esculturas conceptuales, precios/envíos por acordar, cancelación de solicitudes, reclamaciones y derechos del consumidor. Se han evitado plazos de entrega, porcentajes de anticipo, exclusiones de garantía o períodos arbitrarios de devolución no confirmados.

Las condiciones comerciales concretas —importe, impuestos que correspondan, envío, medios de pago, plazos y particularidades del encargo— se documentan antes de aceptar cada compra. La política de conservación usa criterios de necesidad y obligaciones aplicables; no afirma que exista una eliminación automática de datos.

## Conectar el buzón y probarlo

Sigue [correo-cpanel.md](correo-cpanel.md): completa las variables del buzón en el `.env` del servidor, ejecuta `php artisan config:cache` y `php artisan queue:restart`, y prueba con `php artisan studio:mail-test --send`. Activa un worker o el cron de cPanel para procesar los avisos de la web. La contraseña SMTP se introduce sólo en el servidor, no desde Git ni en un campo público del panel.

## Scroll de consola

El fader global se entrega con los recursos compilados; no necesita configuración del panel ni de la base de datos. En escritorio de al menos 900 px con mouse de precisión se muestran guía, escala, cabezal metálico dorado y botones de avance. El desplazamiento sigue siendo nativo: rueda, navegación, enlaces y restauración de scroll funcionan sin un motor de scroll adicional.

Admite arrastre, clic sobre la guía, flechas, Page Up/Down, Inicio/Fin y barra espaciadora. El control informa del porcentaje mediante atributos accesibles. En móvil, pantallas estrechas y modos de contraste forzado se conserva la barra nativa; en diálogos modales el fader se oculta. No guarda preferencias en cookies ni almacenamiento local.

## Fuentes de referencia

- [Ley 172-13 publicada por la Superintendencia de Bancos](https://www.sb.gob.do/media/4i1ploou/ley17213.pdf): información del responsable, finalidad, seguridad y derechos sobre datos personales.
- [Pro Consumidor: atención de reclamaciones](https://proconsumidor.gob.do/conflictos-de-consumo-conoce-los-pasos-para-presentar-una-reclamacion/) y [garantías del consumidor](https://proconsumidor.gob.do/pro-consumidor-lanza-campana-exige-tu-garantia-como-derecho-legal-de-los-consumidores/): tratamiento de incidencias sin renuncias generales a derechos legales.
- [cPanel: configuración de cliente de correo](https://docs.cpanel.net/cpanel/email/set-up-mail-client/) y [Email Deliverability](https://docs.cpanel.net/cpanel/email/email-deliverability-in-cpanel/).

Estos textos corresponden a la operativa descrita y a las respuestas disponibles; no certifican la adecuación de futuras condiciones comerciales o ventas en otras jurisdicciones. La autorización para preparar y publicar esta versión la dio el usuario en esta conversación.
