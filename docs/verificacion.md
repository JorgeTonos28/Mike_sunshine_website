# Verificación de la implementación

Revisión local de los días 21 y 22 de octubre de 2026 con PHP 8.4.7, Node 22.22.1 y SQLite. Composer fija PHP 8.3 como plataforma de resolución; GitHub Actions comprueba esa versión con una instalación limpia.

## Comprobaciones automatizadas

- `php artisan test --compact`: 56 pruebas, 507 aserciones, todas correctas.
- `php vendor/bin/pint --test`: correcto.
- `npm run format:check`: correcto.
- `npm run build`: comprobación de tipos Vue/TypeScript y compilación correctas.
- `composer validate --no-check-publish`: correcto.
- `composer audit --locked` y `npm audit --audit-level=high`: sin vulnerabilidades reportadas en la revisión.
- Caché de rutas generada correctamente y retirada para continuar el desarrollo.

Las pruebas cubren autorización por rol, acceso local/Google simulado, bloqueo de registro público, protección del último administrador, subidas y propiedad de imágenes, bloques de contenido, borradores, precios tomados del servidor, cantidades/disponibilidad, solicitudes en espera con instantáneas, duplicados, consentimiento, cifrado de datos personales y trabajos de cola, recuperación frente a fallos de correo, cabeceras de seguridad, páginas de servicios y rechazo de enlaces sociales inseguros. Se añadió cobertura del seeder: separación del Alquimista correcto, ejecución repetida sin duplicados, corrección de identidad conservando ID/datos comerciales y preservación de títulos, alt y contacto personalizados.

## Revisión en navegador

Se revisaron portada, catálogo, detalle de obra, carrito, solicitud, comprobante, contacto, páginas legales y administración. Se completó una solicitud de demostración y se comprobó su aparición en el panel. También se creó y guardó un borrador con título y tabla desde el editor.

Se revisaron anchos de 1440, 768 y 390 píxeles, incluido el menú móvil, formularios y panel. Las vistas revisadas no presentaron desplazamiento horizontal ni imágenes rotas. La portada y la navegación de catálogo también se comprobaron en modo de producción local con CSP activa, sin errores de consola de seguridad. Las capturas de revisión permanecen en `output/review/` y no se versionan.

La cuenta, el borrador, los pedidos y las consultas ficticios usados en la revisión se retiraron de la base local, junto con sus avisos pendientes. Permanecen ocho pinturas y cuatro conceptos escultóricos para revisar el diseño; no tienen precios ni medidas inventadas. No se enviaron correos externos ni se procesaron pagos.

En la segunda revisión se comprobó la apertura del modal desde el banner de Contacto y desde un servicio, el mensaje predefinido y el envío real a la base local. Se solicitaron tres unidades de una escultura sin existencias desde su ficha, se conservaron en el carrito y se verificó la condición de espera tanto en el comprobante como en la instantánea del pedido. Se comprobó el pincel: aparece al mover el mouse, las gotas desaparecen en reposo y el control del pie lo desactiva. Las nuevas páginas se revisaron a 390 px sin desplazamiento horizontal ni imágenes rotas. El modal de servicios también se abrió en modo de producción local sin errores de CSP.

En la tercera revisión se comprobó la rotación ascendente del pincel y su punto de clic, los banners reflejados con texto a la derecha, el mandril con alfa y silueta completa y el nuevo hero del Alquimista en Esculturas. Se revisaron Inicio a 1440/768/390 px y Esculturas a 1440/390 px, sin desbordamiento horizontal ni imágenes rotas. Se verificó la ficha `/obras/arabe-alquimista-turbante` con su imagen correcta y la opción de añadirla al carrito.

Las entradas del hero tienen retrasos de 0–300 ms; el arte entra en 1000 ms y los bloques se revelan en 650 ms. En el navegador se comprobó el paso de bloques pendientes a visibles al bajar y que permanecen visibles al volver arriba. El código contempla `prefers-reduced-motion`, foco de teclado y ausencia de IntersectionObserver; no se emuló un cambio de preferencia del sistema operativo. Se comprobó Inicio con `APP_ENV=production`, CSP activa y sin errores de consola. Capturas nuevas: `output/review/adjustments/{home-artist,home-banner,sculptures-desktop}.jpg`.

En la cuarta revisión se comprobó la portada con el hero centrado a 1920 y 2560 px: su capa deja de crecer a 1440 px y mantiene su posición respecto al contenedor del texto. Se revisaron los dos accesos con imágenes transparentes a 1440, 768 y 390 px, sin desbordamiento horizontal ni recortes; las miniaturas WebP conservan alfa y tienen 320 × 400 px. Se revisó el hero de Pinturas sin panel negro y la ficha del Alquimista con fondo carbón. Compilación, TypeScript, formato y sintaxis del conversor PHP correctos; sin errores de consola en las vistas revisadas. Capturas en `output/review/background-fixes/`.

En la quinta revisión se añadió `/sobre-mi`, se revisó su composición a 1440 y 390 px y se comprobó la navegación ampliada a 768 px, sin desbordamiento horizontal ni imágenes rotas. La página y el modal del servicio de encargos se abrieron con CSP de producción activa y sin errores de consola. Se comprobó la redirección 301 de la ruta antigua del servicio y el mensaje predefinido con «encargo». La revisión de código y contenido público guardado no encontró usos del término anterior fuera de la ruta de compatibilidad y su prueba.

Se verificó desde el navegador el flujo completo del retrato: iniciar sesión con una cuenta temporal local, seleccionar una foto, ver la vista previa Blob, guardar, obtener una nueva ruta `/media/{uuid}.webp` y verla en Sobre mí. Después se restauró el retrato inicial y se retiraron la cuenta, su sesión y el archivo de prueba. Las pruebas automatizadas cubren sustitución, recodificación, rechazo de archivos inválidos o demasiado grandes, rechazo de rutas arbitrarias, permisos de administrador, descarga del cuestionario y exclusión de obras privadas en la nueva galería. La migración se aplicó sobre la base local existente y se ejecuta desde cero en las pruebas.

Capturas de esta revisión: `output/review/about/{cover-desktop,cover-mobile,page-desktop,admin-portrait,encargo-modal}.jpg`. El cuestionario biográfico es distinto del cuestionario de privacidad y condiciones comerciales; sus 24 preguntas alimentan los textos e imágenes de la página del artista.

En la sexta revisión se ajustó el recorte del retrato al arco inferior conservando la cabeza sobre el aro. Se comprobó la luz orbital con mouse y teclado, pausa y reubicación al pulsarla. La luz y la estela se dibujan delante en la mitad inferior; la mitad superior pasa detrás del retrato. La estela usa un arco continuo con degradado y halo difuminado. Se revisaron escritorio y 390 px, sin desbordamiento ni imágenes rotas; también se abrió Sobre mí con CSP de producción activa, sin errores de consola. El código contempla movimiento reducido y limpieza de observadores, temporizadores y frames al salir de la página; no se emuló una preferencia del sistema operativo. Capturas en `output/review/orbit/`.

Se añadieron pruebas del transporte Gmail con TLS obligatorio y timeout acotado, prioridad del buzón privado sobre el contacto público, destinatarios inválidos, diagnóstico sin envío, envío explícito simulado sin crear datos de clientes, bloqueo del transporte log como prueba de entrega y errores sin exposición de contraseñas. `php artisan studio:mail-test` se ejecutó en el entorno local y confirmó el transporte log. No se configuraron credenciales reales ni se envió correo externo. Los pasos para comprobar una entrega real, el panel y los avisos de la cola están en `docs/probar-panel-y-gmail.md`.

En la séptima revisión se versionaron las fichas del catálogo local en `database/catalog/artworks.json`, con ocho pinturas y cuatro esculturas conceptuales, sin exportar usuarios, contactos, pedidos ni credenciales. `studio:catalog-install` carga únicamente obras faltantes y sus imágenes. Las pruebas cubren producción, revisión sin escritura, ejecución repetida, conservación de precios/textos/borradores/archivadas, detección de obras renombradas por imagen, errores de formato, rutas inseguras, imágenes ausentes, rollback completo y páginas públicas con las ocho pinturas y cuatro esculturas. Se conservó el seeder local de contactos y las correcciones de identidad existentes, usando el mismo catálogo.

También se ejecutaron migraciones y el comando real con `APP_ENV=production` sobre una base SQLite temporal independiente: revisión con 12 pendientes, primera instalación con 12 creadas y segunda con 0 creadas y 12 conservadas. Esa base se retiró después de la comprobación. La base local y el servidor de producción del usuario no se modificaron. Instrucciones del despliegue y comando de carga en `docs/instalar-catalogo.md`.

## Configuración pendiente para el lanzamiento

- Crear la cuenta real de Mike y configurar las credenciales/callback de Google. La conexión con Google real requiere esa configuración; las pruebas actuales simulan el proveedor y verifican su estado y sus restricciones de acceso.
- Configurar SMTP, destinatario del estudio y supervisor de cola; verificar la entrega de un correo real en el entorno de despliegue.
- Completar identidad, contacto, moneda, imágenes aprobadas, títulos, precios, disponibilidad y condiciones de envío. Sustituir los datos de contacto de muestra y enlaces `/#`. Mike debe revisar los cuatro conceptos escultóricos antes de ofrecer su producción.
- Responder `cuestionario-mike.md`, ajustar y aprobar privacidad, términos y cookies desde el panel.
- Configurar dominio, HTTPS, base de producción y copias de seguridad. Esta revisión funcional usa SQLite local y no equivale a una verificación del servidor MySQL de despliegue.

El usuario ha informado de un despliegue de producción. Esta revisión no accedió a ese servidor ni ejecutó allí la carga del catálogo; se verificó el mecanismo de instalación en un entorno aislado. No se afirma una puntuación de Lighthouse ni se ha realizado una prueba de carga.
