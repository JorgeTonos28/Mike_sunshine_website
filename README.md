# Mike Sunshine

Galería y catálogo de pinturas y esculturas 3D. Pedidos por solicitud y confirmación manual, sin pasarela de pago.

## Tecnología

Laravel 13 / PHP 8.3+, Inertia, Vue 3, TypeScript, Vite y Tailwind 4. SQLite para desarrollo; MySQL para despliegue.

Referencia de ingeniería: [Vamo al game](https://github.com/JorgeTonos28/Vamo_al_game). Se adoptan separación de responsabilidades, validación en servidor, autorización y cabeceras de seguridad. El proyecto es exclusivamente web responsive.

## Desarrollo

1. `composer install`
2. Copiar `.env.example` a `.env` y configurar el entorno.
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `npm ci && npm run build`
6. `php artisan serve`

Crear el primer administrador: `php artisan studio:admin correo@ejemplo.com` (contraseña interactiva, nunca compartida en seeds). No hay registro público. Google necesita `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` y callback exacto en `GOOGLE_REDIRECT_URI`. Sólo acceden cuentas activas previamente creadas; no se guardan tokens del proveedor. Recuperación de contraseña disponible con correo configurado. En producción: HTTPS, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true` y una `APP_KEY` estable respaldada. Cambiar la clave pierde el acceso a los datos cifrados.

Las obras de muestra son representaciones gráficas derivadas de referencias proporcionadas por el artista. Los precios, dimensiones y disponibilidad reales se completan desde administración. No se generan esculturas ficticias para el catálogo.

## Administración

`/admin` permite crear/editar pinturas y esculturas, publicar o guardar borradores, indicar disponibilidad, precio opcional, técnica, dimensiones, edición, año y galería. Los detalles se componen con bloques de título, párrafo, viñetas, lista numerada y tabla; no admiten HTML ejecutable. Se exige imagen para publicar. Las subidas PNG/JPG/WebP se verifican, recodifican a WebP y sirven desde `/media` sin exponer rutas internas. Máximo 8 MB y 12 megapíxeles.

Administradores: usuarios/roles, datos del negocio, logo/favicon/hero, redes y textos legales. Editores: catálogo únicamente. Un administrador activo debe permanecer. Archivar una obra la oculta y conserva el historial.
