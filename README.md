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

Para cargar las representaciones de demostración en local: `php artisan db:seed --class=DemoArtworkSeeder`. Este seeder se bloquea en producción. Las carpetas originales `Mockups/` y `Muestras de pinturas/` permanecen locales; los recursos usados están versionados en `public/images`. Procedencia y prompts en `docs/imagenes.md`.

Crear el primer administrador: `php artisan studio:admin correo@ejemplo.com` (contraseña interactiva, nunca compartida en seeds). No hay registro público. Google necesita `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` y callback exacto en `GOOGLE_REDIRECT_URI`. Sólo acceden cuentas activas previamente creadas; no se guardan tokens del proveedor. Recuperación de contraseña disponible con correo configurado. En producción: HTTPS, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` y una `APP_KEY` estable respaldada. Cambiar la clave pierde el acceso a los datos cifrados.

Las obras de muestra son representaciones gráficas derivadas de referencias proporcionadas por el artista. Los precios, dimensiones y disponibilidad reales se completan desde administración. No se generan esculturas ficticias para el catálogo.

## Administración

La galería permite reordenar imágenes, elegir la portada como primera imagen y editar las descripciones accesibles desde el panel.

`/admin` permite crear/editar pinturas y esculturas, publicar o guardar borradores, indicar disponibilidad, precio opcional, técnica, dimensiones, edición, año y galería. Los detalles se componen con bloques de título, párrafo, viñetas, lista numerada y tabla; no admiten HTML ejecutable. Se exige imagen para publicar. Las subidas PNG/JPG/WebP se verifican, recodifican a WebP y sirven desde `/media` sin exponer rutas internas. Máximo 8 MB y 12 megapíxeles.

Administradores: usuarios/roles, datos del negocio, logo/favicon/hero, redes y textos legales. Editores: catálogo únicamente. Un administrador activo debe permanecer. Archivar una obra la oculta y conserva el historial.

El cuestionario de negocio está en [docs/cuestionario-mike.md](docs/cuestionario-mike.md), descargable también desde Páginas legales en el panel. Incluye identidad, catálogo, derechos/licencias, confirmación/pagos manuales, envíos, cancelaciones, devoluciones, privacidad, retención, proveedores y cookies. Los textos legales iniciales son provisionales, editables por bloques y con aprobación/versionado explícitos.

## Pedidos y correo

Carrito en sesión, formulario de cliente/entrega, aceptación de privacidad y términos, comprobación final de disponibilidad en servidor y protección contra envíos duplicados. Los importes se toman de la base y se guardan como instantánea. Sin precio significa consulta; envío por cotizar. No se reserva inventario ni se cobra automáticamente. Mike gestiona estados, notas y disponibilidad desde el panel.

Configurar correo SMTP y el email del estudio desde ajustes o `STUDIO_EMAIL`. Ejecutar `php artisan queue:work database --tries=5 --timeout=30` bajo un supervisor en producción. Pedido y aviso de cola se guardan en una transacción en la misma conexión de base de datos (mantener `DB_QUEUE_CONNECTION` vacío). El email sólo contiene referencia y enlace privado; los datos del cliente permanecen en el panel. Fallos se muestran en cada solicitud y admiten reintento. Local usa `MAIL_MAILER=log`, por lo que no envía correos reales. El registro persiste incluso si SMTP no está configurado.

## Verificación

`php artisan test`, `php vendor/bin/pint --test`, `npm run format:check` y `npm run build` (incluye typecheck). GitHub Actions repite estas comprobaciones con PHP 8.3 y Node 22, instalación limpia, migraciones y auditorías de dependencias. Fuentes alojadas en el proyecto, sin peticiones a Google Fonts. La portada conserva su PNG maestro y usa un derivado WebP con alfa para la entrega.

Resultados y alcance de la revisión en [docs/verificacion.md](docs/verificacion.md).

## Despliegue

El document root debe apuntar a `public/`. Configurar base de datos, `.env` fuera de acceso público, `APP_URL` HTTPS, correo, clave estable, permisos de `storage` y `bootstrap/cache`. Compilar con `npm ci && npm run build`, instalar backend con `composer install --no-dev --optimize-autoloader`, ejecutar `php artisan migrate --seed --force` y `php artisan optimize`. Ejecutar el worker database con supervisor y respaldar base de datos, `storage/app/public` y la clave de cifrado de forma segura. No cargar el seeder de demostración en producción.

Antes de lanzamiento: crear la cuenta de Mike, configurar Google Cloud y SMTP, completar datos del negocio, revisar imágenes/títulos/precios reales y aprobar los textos legales tras responder el cuestionario.
