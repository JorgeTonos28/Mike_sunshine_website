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

## Pedidos y correo

Carrito en sesión, formulario de cliente/entrega, aceptación de privacidad y términos, comprobación final de disponibilidad en servidor y protección contra envíos duplicados. Los importes se toman de la base y se guardan como instantánea. Sin precio significa consulta; envío por cotizar. No se reserva inventario ni se cobra automáticamente. Mike gestiona estados, notas y disponibilidad desde el panel.

Configurar correo SMTP y el email del estudio desde ajustes o `STUDIO_EMAIL`. Ejecutar `php artisan queue:work database --tries=5 --timeout=30` bajo un supervisor en producción. Pedido y aviso de cola se guardan en una transacción en la misma conexión de base de datos (mantener `DB_QUEUE_CONNECTION` vacío). El email sólo contiene referencia y enlace privado; los datos del cliente permanecen en el panel. Fallos se muestran en cada solicitud y admiten reintento. Local usa `MAIL_MAILER=log`, por lo que no envía correos reales. El registro persiste incluso si SMTP no está configurado.
