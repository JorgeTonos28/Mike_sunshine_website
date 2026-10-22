# Verificación de la implementación

Revisión local de los días 21 y 22 de octubre de 2026 con PHP 8.4.7, Node 22.22.1 y SQLite. Composer fija PHP 8.3 como plataforma de resolución; GitHub Actions comprueba esa versión con una instalación limpia.

## Comprobaciones automatizadas

- `php artisan test --compact`: 29 pruebas, 303 aserciones, todas correctas.
- `php vendor/bin/pint --test`: correcto.
- `npm run format:check`: correcto.
- `npm run build`: comprobación de tipos Vue/TypeScript y compilación correctas.
- `composer validate --no-check-publish`: correcto.
- `composer audit --locked` y `npm audit --audit-level=high`: sin vulnerabilidades reportadas en la revisión.
- Caché de rutas generada correctamente y retirada para continuar el desarrollo.

Las pruebas cubren autorización por rol, acceso local/Google simulado, bloqueo de registro público, protección del último administrador, subidas y propiedad de imágenes, bloques de contenido, borradores, precios tomados del servidor, cantidades/disponibilidad, solicitudes en espera con instantáneas, duplicados, consentimiento, cifrado de datos personales y trabajos de cola, recuperación frente a fallos de correo, cabeceras de seguridad, páginas de servicios y rechazo de enlaces sociales inseguros.

## Revisión en navegador

Se revisaron portada, catálogo, detalle de obra, carrito, solicitud, comprobante, contacto, páginas legales y administración. Se completó una solicitud de demostración y se comprobó su aparición en el panel. También se creó y guardó un borrador con título y tabla desde el editor.

Se revisaron anchos de 1440, 768 y 390 píxeles, incluido el menú móvil, formularios y panel. Las vistas revisadas no presentaron desplazamiento horizontal ni imágenes rotas. La portada y la navegación de catálogo también se comprobaron en modo de producción local con CSP activa, sin errores de consola de seguridad. Las capturas de revisión permanecen en `output/review/` y no se versionan.

La cuenta, el borrador, los pedidos y las consultas ficticios usados en la revisión se retiraron de la base local, junto con sus avisos pendientes. Permanecen siete pinturas y cuatro conceptos escultóricos para revisar el diseño; no tienen precios ni medidas inventadas. No se enviaron correos externos ni se procesaron pagos.

En la segunda revisión se comprobó la apertura del modal desde el banner de Contacto y desde un servicio, el mensaje predefinido y el envío real a la base local. Se solicitaron tres unidades de una escultura sin existencias desde su ficha, se conservaron en el carrito y se verificó la condición de espera tanto en el comprobante como en la instantánea del pedido. Se comprobó el pincel: aparece al mover el mouse, las gotas desaparecen en reposo y el control del pie lo desactiva. Las nuevas páginas se revisaron a 390 px sin desplazamiento horizontal ni imágenes rotas. El modal de servicios también se abrió en modo de producción local sin errores de CSP.

## Configuración pendiente para el lanzamiento

- Crear la cuenta real de Mike y configurar las credenciales/callback de Google. La conexión con Google real requiere esa configuración; las pruebas actuales simulan el proveedor y verifican su estado y sus restricciones de acceso.
- Configurar SMTP, destinatario del estudio y supervisor de cola; verificar la entrega de un correo real en el entorno de despliegue.
- Completar identidad, contacto, moneda, imágenes aprobadas, títulos, precios, disponibilidad y condiciones de envío. Sustituir los datos de contacto de muestra y enlaces `/#`. Mike debe revisar los cuatro conceptos escultóricos antes de ofrecer su producción.
- Responder `cuestionario-mike.md`, ajustar y aprobar privacidad, términos y cookies desde el panel.
- Configurar dominio, HTTPS, base de producción y copias de seguridad. Esta revisión funcional usa SQLite local y no equivale a una verificación del servidor MySQL de despliegue.

No se ha publicado un servidor de producción. No se afirma una puntuación de Lighthouse ni se ha realizado una prueba de carga.
