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

Las obras de muestra son representaciones gráficas derivadas de referencias proporcionadas por el artista. Los precios, dimensiones y disponibilidad reales se completan desde administración. No se generan esculturas ficticias para el catálogo.
