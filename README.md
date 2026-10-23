# Mike Sunshine

Galería y catálogo de pinturas y esculturas 3D. Pedidos por solicitud y confirmación manual, sin pasarela de pago.

## Tecnología

Laravel 13 / PHP 8.3+, Inertia, Vue 3, TypeScript, Vite y Tailwind 4. SQLite para desarrollo; MySQL para despliegue.

Composer resuelve sus dependencias para PHP 8.3 mediante `config.platform.php`; el entorno local también funciona con PHP 8.4. Node 22.12+.

Se requieren las extensiones PHP de Laravel y GD/fileinfo para validar y recodificar imágenes, además de PDO SQLite o MySQL según la base elegida.

Referencia de ingeniería: [Vamo al game](https://github.com/JorgeTonos28/Vamo_al_game). Se adoptan separación de responsabilidades, validación en servidor, autorización y cabeceras de seguridad. El proyecto es exclusivamente web responsive.

## Desarrollo

1. `composer install`
2. Copiar `.env.example` a `.env` y configurar el entorno.
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `npm ci && npm run build`
6. `php artisan serve`

Las ocho pinturas y cuatro esculturas conceptuales preparadas están versionadas en `database/catalog/artworks.json`, con sus imágenes en `public/images/art/` y `public/images/sculptures/`. Para cargarlas en producción después de actualizar el despliegue: **`php artisan studio:catalog-install --force`**. `--dry-run` permite revisar sin guardar. Es repetible: sólo crea obras faltantes y conserva las existentes, incluso borradores y archivadas. No importa usuarios, pedidos ni datos del negocio. Instrucciones: [docs/instalar-catalogo.md](docs/instalar-catalogo.md).

Para completar también los datos de contacto desechables de la revisión local: `php artisan db:seed --class=DemoArtworkSeeder`. Ese seeder sigue bloqueado en producción y usa el mismo catálogo. Las carpetas originales `Mockups/` y `Muestras de pinturas/` permanecen locales; los recursos usados están versionados en `public/images`. Procedencia y prompts en `docs/imagenes.md`.

Crear el primer administrador: `php artisan studio:admin correo@ejemplo.com` (contraseña interactiva, nunca compartida en seeds). No hay registro público. Google necesita `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` y callback exacto en `GOOGLE_REDIRECT_URI`. Sólo acceden cuentas activas previamente creadas; no se guardan tokens del proveedor. Recuperación de contraseña disponible con correo configurado. En producción: HTTPS, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` y una `APP_KEY` estable respaldada. Cambiar la clave pierde el acceso a los datos cifrados.

Las obras de muestra son representaciones gráficas derivadas de referencias proporcionadas por el artista. El catálogo local incluye ocho pinturas y cuatro esculturas conceptuales solicitadas por el usuario, basadas en esas pinturas e identificadas como conceptos. El Árabe Alquimista es el personaje del turbante y el frasco verde; el personaje del gorro moteado tiene una ficha distinta. El seeder corrige la identificación anterior sin reemplazar la ficha ni sus datos comerciales y respeta títulos personalizados. Los precios, dimensiones y existencias reales se completan desde administración.

Los heroes de Inicio, Pinturas, Esculturas y Contacto usan personajes distintos. Esculturas muestra una interpretación conceptual del Árabe Alquimista. Los cuatro banners tienen composiciones pictóricas y textos propios, con el personaje a la izquierda y el texto a la derecha en escritorio; las imágenes existentes se reflejan con CSS. Sus PNG maestros, derivados WebP y prompts están en `public/images/compositions/`, `docs/prompts-composiciones.json`, `docs/prompts-ajustes.json` y `docs/prompts-fondos.json`. La sección del estudio usa un mandril con alfa, sin recorte rectangular.

La ilustración del hero de Inicio tiene un marco centrado de hasta 1440 px para mantener su relación con el texto en pantallas anchas. Los accesos a Pinturas y Esculturas usan recortes transparentes del gato pintado y del mago escultórico, con WebP de 320 px y encuadre completo. El hero de Pinturas conserva alfa fuera de la figura y las pinceladas; la reproducción del Alquimista usa fondo carbón, como las demás pinturas del catálogo.

Inicio anima de forma escalonada los elementos del hero y revela categorías, obras, estudio y banner al entrar en pantalla. Cada elemento se revela una vez por visita mediante IntersectionObserver, sin bucle continuo. El contenido es visible por defecto si no se instala el observador; se omiten las animaciones con movimiento reducido y se revela cualquier bloque que reciba foco de teclado.

## Administración

La galería permite reordenar imágenes, elegir la portada como primera imagen y editar las descripciones accesibles desde el panel.

`/admin` permite crear/editar pinturas y esculturas, publicar o guardar borradores, indicar disponibilidad, precio opcional, técnica, dimensiones, edición, año y galería. Los detalles se componen con bloques de título, párrafo, viñetas, lista numerada y tabla; no admiten HTML ejecutable. Se exige imagen para publicar. Las subidas PNG/JPG/WebP se verifican, recodifican a WebP y sirven desde `/media` sin exponer rutas internas. Máximo 8 MB y 12 megapíxeles.

Administradores: usuarios/roles, datos del negocio, logo/favicon/hero, redes y textos legales. Editores: catálogo únicamente. Un administrador activo debe permanecer. Archivar una obra la oculta y conserva el historial.

El cuestionario de negocio está en [docs/cuestionario-mike.md](docs/cuestionario-mike.md), descargable también desde Páginas legales en el panel. Incluye identidad, catálogo, derechos/licencias, confirmación/pagos manuales, envíos, cancelaciones, devoluciones, privacidad, retención, proveedores y cookies. Los textos de lanzamiento están preparados en `LaunchContent`: `php artisan studio:launch-install --force` carga el contacto confirmado y publica Privacidad, Términos y Cookies, respaldando el contenido previo. Sólo se aplica una vez; las ediciones posteriores del panel se conservan. Guía: [docs/lanzamiento.md](docs/lanzamiento.md).

Inicio y Contacto muestran los datos básicos y las redes configurados. El lanzamiento carga el correo público de Mike, teléfono/WhatsApp, domicilio e Instagram confirmados, y deja Facebook/YouTube sin enlaces. El seeder de demostración sigue limitado a local y sólo completa campos vacíos con contacto desechable; no debe usarse después de preparar los datos reales.

## Sobre el artista

`/sobre-mi` presenta a Mike con una composición editorial propia: apertura tipográfica, retrato, presentación, conexión entre disciplinas, proceso creativo, galería y una invitación a conversar. Tiene acceso desde la navegación, el pie y la sección del artista en Inicio. La galería muestra sólo obras publicadas con imagen, excluyendo borradores y piezas archivadas. La página también funciona con un catálogo vacío.

La foto se cambia en **Información del negocio → Retrato del artista**. Incluye vista previa antes de guardar; admite PNG, WebP o JPG de hasta 8 MB y 12 megapíxeles, comprobados y recodificados como WebP. Sólo los administradores pueden modificarla. La migración añade `portrait_path` con el retrato inicial; ejecutar `php artisan migrate` al actualizar una instalación existente. La biografía breve se toma del campo «Sobre el artista» del mismo panel, compartido con Inicio.

El retrato conserva la cabeza fuera del aro y se recorta sobre su arco inferior. Una luz dorada recorre la órbita con una estela continua de brillo difuminado: pasa detrás del retrato por arriba y delante por abajo. Al pulsarla desaparece y reaparece en otro punto. SVG y Vue gestionan el efecto sin dependencias 3D; hay pausa, acceso por teclado y movimiento reducido. El bucle se detiene fuera de pantalla y con la pestaña oculta.

El cuestionario de 24 preguntas está en [docs/cuestionario-sobre-mike.md](docs/cuestionario-sobre-mike.md) y se descarga desde el bloque del retrato en el panel. Recoge historia, disciplinas, proceso, imágenes y voz editorial. Los demás textos de la página son una propuesta inicial; fechas, premios, clientes y citas se añadirán únicamente cuando Mike los confirme. El retrato se preparó con la herramienta integrada de imágenes; PNG maestro, WebP y prompt están documentados en [docs/imagenes.md](docs/imagenes.md).

## Contacto y servicios

Contacto incluye formulario principal, canales con íconos y la sección “¿Qué puedes solicitar?”. Cada opción abre una página dedicada: `/servicios/pinturas`, `/servicios/esculturas-3d`, `/servicios/encargos-personalizados` y `/servicios/eventos-colaboraciones`. Se usa «encargo» en banners, formularios, mensajes y metadatos; la antigua ruta del servicio redirige permanentemente a la nueva. Los CTA abren un modal accesible con asunto y mensaje preparados para ese servicio; el visitante completa sus datos y consentimiento. Puede editar el mensaje. “Escríbeme ahora” abre el formulario general desde el banner de Contacto. Las consultas se guardan cifradas y generan su aviso en cola.

El efecto de pincel apunta hacia arriba y alinea la punta con el punto de clic. Se activa con mouse de precisión y respeta `prefers-reduced-motion`. Deja gotas sólo al moverse, limitadas a 24 elementos y retiradas en 460 ms; no hay un bucle continuo en reposo. Conserva el cursor habitual en campos de texto y modales. Se puede desactivar desde el pie; la preferencia dura durante la sesión de la página, sin cookies ni almacenamiento de seguimiento.

## Pedidos y correo

Carrito en sesión, formulario de cliente/entrega, aceptación de privacidad y términos, comprobación final de disponibilidad en servidor y protección contra envíos duplicados. Los importes se toman de la base y se guardan como instantánea. Sin precio significa consulta; envío por cotizar. No se reserva inventario ni se cobra automáticamente. Mike gestiona estados, notas y disponibilidad desde el panel.

La cantidad se elige en la ficha y se puede cambiar en el carrito (1 a 10 por obra y hasta 20 obras distintas). Toda obra publicada permite una solicitud aunque tenga cero existencias, esté reservada/vendida o se pida más de lo disponible. En esos casos se avisa en ficha, carrito, formulario y comprobante de que Mike contactará cuando haya disponibilidad. El servidor marca la solicitud “En espera de disponibilidad” y conserva esa condición por línea, aunque el stock cambie después. Los borradores y las obras archivadas siguen excluidos. Las existencias informan sobre disponibilidad; no se reservan al enviar una solicitud.

La configuración elegida para producción es el buzón de cPanel `orders@mikesunshine.com`: `MAIL_MAILER=cpanel`, host `mail.mikesunshine.com`, SMTPS obligatorio en puerto 465 y timeout de 15 segundos. Completa `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` y `STUDIO_EMAIL` en el servidor. `STUDIO_EMAIL` tiene prioridad sobre el correo público del panel. `php artisan studio:mail-test` diagnostica sin conectar; `--send` envía una prueba explícita sin crear pedidos. Configuración, pruebas y cron para hosting compartido: [docs/correo-cpanel.md](docs/correo-cpanel.md). El perfil Gmail permanece como alternativa técnica, aunque ya no es el elegido para órdenes.

Ejecutar `php artisan queue:work database --tries=5 --timeout=30` bajo un supervisor en producción. Pedido y aviso de cola se guardan en una transacción en la misma conexión de base de datos (mantener `DB_QUEUE_CONNECTION` vacío). El email sólo contiene referencia y enlace privado; los datos del cliente permanecen en el panel. Fallos se muestran en cada solicitud y admiten reintento. Local usa `MAIL_MAILER=log`, por lo que no envía correos reales. El registro persiste incluso si SMTP no está configurado.

## Verificación

`php artisan test`, `php vendor/bin/pint --test`, `npm run format:check` y `npm run build` (incluye typecheck). GitHub Actions repite estas comprobaciones con PHP 8.3 y Node 22, instalación limpia, migraciones y auditorías de dependencias. Fuentes alojadas en el proyecto, sin peticiones a Google Fonts. La portada conserva su PNG maestro y usa un derivado WebP con alfa para la entrega.

Resultados y alcance de la revisión en [docs/verificacion.md](docs/verificacion.md).

## Despliegue

El document root debe apuntar a `public/`. Configurar base de datos, `.env` fuera de acceso público, `APP_URL` HTTPS, correo, clave estable, permisos de `storage` y `bootstrap/cache`. Compilar con `npm ci && npm run build`, instalar backend con `composer install --no-dev --optimize-autoloader`, ejecutar `php artisan migrate --seed --force`, `php artisan studio:catalog-install --force`, `php artisan studio:launch-install --force` y `php artisan optimize`. Ambos instaladores son repetibles y conservan las ediciones posteriores del panel. Ejecutar el worker database con supervisor o cron de cPanel y respaldar base de datos, `storage/app/public` y la clave de cifrado de forma segura. No cargar el seeder de contactos de demostración en producción.

Para activar la última actualización: aplicar la migración y `studio:launch-install`, configurar SMTP de cPanel y el worker/cron, y verificar una entrega real. Mike debe confirmar precios, datos individuales de las obras y cualquier condición particular de compra antes de aceptar pedidos. Google Cloud sólo es necesario si se quiere habilitar ese acceso al panel. El scroll global usa un fader accesible en escritorio y conserva desplazamiento nativo en móvil.
