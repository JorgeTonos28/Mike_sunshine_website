# Cargar las pinturas y esculturas en producción

Las imágenes ya están versionadas en `public/images/art/` y `public/images/sculptures/`. Las fichas necesitan filas en la base de datos de cada instalación: desplegar código desde GitHub no copia la base de datos local.

El catálogo preparado está ahora en `database/catalog/artworks.json`, junto con el instalador que carga sus datos e imágenes en la base del servidor. Incluye las ocho pinturas y las cuatro esculturas conceptuales que revisamos, incluido El Árabe Alquimista con su imagen correcta, títulos, categorías, descripciones, bloques de detalle, orden de imágenes, textos alternativos, publicación y disponibilidad inicial.

Los precios y dimensiones siguen sin definir, tal como están en el catálogo local. Las esculturas mantienen sus textos de concepto y cero existencias; permiten solicitar disponibilidad. Los valores comerciales reales se completan en el panel.

## En tu instalación actual de producción

Actualiza el despliegue desde `main` como haces habitualmente, incluyendo `database/catalog/artworks.json` y las imágenes de `public/images/`. En la terminal del servidor, desde la raíz de Laravel donde está `artisan`, ejecuta:

```bash
php artisan studio:catalog-install --force
```

`--force` habilita esta operación con `APP_ENV=production`. No hace falta ejecutar el seeder de demostración ni cambiar el entorno a local. No necesitas vaciar tablas, importar un SQL ni modificar `.env` para esta carga.

Si el servidor aún tiene migraciones pendientes, ejecuta primero el paso habitual de actualización:

```bash
php artisan migrate --force
```

El instalador mostrará cuántas pinturas y esculturas añadió y cuántas obras existentes conservó. Si el catálogo de producción estaba vacío, el resultado será **12 añadidas: 8 pinturas y 4 esculturas**.

Comprueba `/pinturas`, `/esculturas` y **Obras** en el panel. También puedes abrir `/obras/arabe-alquimista-turbante` para revisar la pintura que faltaba en las primeras versiones.

## Revisar antes de guardar

Opcionalmente, puedes ver el resultado previsto sin escribir en la base:

```bash
php artisan studio:catalog-install --dry-run
```

La revisión también funciona en producción sin `--force` y comprueba el formato y la presencia de todas las imágenes.

## Repetirlo en futuros despliegues

Puedes añadir `php artisan studio:catalog-install --force` después de las migraciones en tu proceso de despliegue, o ejecutarlo de nuevo cuando necesites incorporar obras nuevas del catálogo versionado.

- Añade sólo las obras que faltan. Detecta las existentes por su identificador URL (`slug`) o por una imagen del mismo tipo de obra.
- Conserva íntegramente sus títulos, precios, stock, textos, galería y publicación, incluidos borradores.
- Respeta obras archivadas: no las restaura ni las vuelve a publicar.
- Si cambias a la vez el slug y todas las imágenes de una obra, pierde esas referencias para reconocerla; conserva al menos una de ellas si vas a repetir la carga inicial.
- Comprueba todos los datos e imágenes antes de insertar. Los cambios se guardan en una transacción: un error de base de datos revierte toda esa carga.
- No importa ni cambia datos del negocio, usuarios, pedidos, consultas, correos ni credenciales.

El catálogo de Git es un punto de partida versionado, no una sincronización permanente con la base de producción. Lo que Mike cree o edite posteriormente desde el panel se guarda en producción y debe incluirse en sus copias de seguridad de base de datos y archivos subidos. El instalador no reemplaza esas ediciones con los valores iniciales del repositorio.

Si falta una imagen, asegúrate de haber desplegado el repositorio completo. Si el comando no existe, el servidor todavía no ha recibido esta actualización; actualiza `main` y las dependencias del despliegue. Usa la misma versión de PHP que ejecuta tu aplicación (8.3 o superior).
