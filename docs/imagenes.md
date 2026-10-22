# Recursos gráficos

Generados con la herramienta integrada de imágenes, usando las fotos suministradas como referencia de contenido. No se han usado fotografías de stock ni se han inventado pinturas. Desde la revisión solicitada el 22 de octubre se incluyen esculturas conceptuales derivadas de esas pinturas, autorizadas expresamente por el usuario e identificadas como conceptos.

| Archivo final | Referencia de contenido | Uso |
| --- | --- | --- |
| `public/images/art/hero-wizard.png` | `Muestras de pinturas/WhatsApp Image 2026-10-19 at 9.18.51 PM (1).jpeg` | Composición de hero con alfa, mago violeta. MSW1 se usó únicamente como referencia de tratamiento pictórico. |
| `public/images/art/hero-wizard.webp` | Mismo PNG maestro | Derivado de entrega con transparencia para reducir el peso descargado, sin cambiar la composición. |
| `public/images/art/wizard.webp` | Misma foto del mago | Reproducción regenerada para catálogo. PNG maestro conservado. |
| `public/images/art/cat.webp` | `WhatsApp Image 2026-10-19 at 9.18.53 PM (1).jpeg` | Gato esfinge. PNG maestro conservado. |
| `public/images/art/mandrill.webp` | `WhatsApp Image 2026-10-19 at 9.18.52 PM (6).jpeg` | Mandril. PNG maestro conservado. |
| `public/images/art/monk.webp` | `WhatsApp Image 2026-10-19 at 9.18.51 PM.jpeg` | Sabio de túnica verde, reproducción regenerada. |
| `public/images/art/jester.webp` | `WhatsApp Image 2026-10-19 at 9.18.52 PM (1).jpeg`, únicamente la pintura derecha | Bufón, reproducción regenerada. |
| `public/images/art/neon.webp` | `WhatsApp Image 2026-10-19 at 9.18.52 PM (5).jpeg` | Personaje de cabello violeta, conservando su lenguaje ilustrado. |
| `public/images/design/brush-gold.png` | Color del logo y pincelada de MSW1 | Subrayado dorado con alfa. Variante `-trimmed` sin márgenes vacíos. |
| `public/images/design/canvas.webp` | Dirección visual de MSW1 | Textura de lienzo carbón, sin personajes. PNG maestro conservado. |
| `public/images/brand/logo-dark-trimmed.png` | `C:/Users/jorge/Downloads/MS4.png` | Variante con alfa y letras marfil para fondo oscuro, conservando el sol. Original preservado como `logo-original.png`. |
| `public/images/brand/favicon.png` | Sol del mismo logo | Extracción regenerada del sol, con alfa. |

## Prompts de producción

Las instrucciones incluyeron estas restricciones explícitas en todas las reproducciones: conservar personaje, facciones, pose, colores, ropa y pinceladas; eliminar entorno fotografiado; no añadir sujetos, objetos, texto, marcos ni marcas de agua. No presentarlas como fotografías de la obra física.

**Hero:** “Use case: compositing. Asset: transparent PNG hero artwork layer for Mike Sunshine artist website, wide 3:2 canvas. Input 1 is the ONLY subject reference: Mike's actual hand painted elderly wizard with long white beard, purple hood with gold edging, large rounded nose, feather in one hand and book. Preserve that exact character, features, costume, expression, colors and painterly character. Remove the photographed paper, studio, table, black surroundings. Re-present this same painted character in the rich oil brushwork treatment of reference 2 (style only, DO NOT use its pirate or invent any new subject). Place the wizard as a large waist-up portrait in the right two thirds, facing toward the left, with irregular painterly silhouette that dissolves into sparse charcoal and muted gold brush strokes at shoulder/base, genuine transparent alpha outside silhouette and strokes. Left third empty transparent negative space for HTML text. Dramatic warm studio light, deep shadows, premium oil canvas texture, fine brush details, purple and antique gold. No text, no letters, no captions, no logo, no watermark, no border, no fake checkerboard, no new props or characters. Website hero should blend seamlessly over #11100e.”

**Mago:** reproducción frontal 4:5 del personaje de la foto, conservando capucha violeta con borde dorado, barba, nariz, pluma y libro; fondo carbón pictórico y textura de óleo. Sin entorno, bordes, tipografía ni objetos añadidos.

**Gato:** reproducción frontal 4:5 exclusivamente del gato esfinge pintado; orejas rosas, ojos ámbar, frente naranja, grises azulados y melocotón, cuello y expresión originales. Sustituir el papel blanco por fondo carbón y preservar la textura de óleo.

**Mandril:** reproducción frontal 4:5 de la pintura, orejas rojas, pelaje azul con mechones blancos, hocico acanalado azul, nariz naranja/roja, mirada hacia arriba y diente visible. Eliminar únicamente entorno, pared, mesa y perspectiva fotográfica.

**Sabio, bufón e ilustración violeta:** “Use case: product-mockup. Asset: straight-on catalogue reproduction, portrait 4:5. Faithfully regenerate ONLY the existing Mike Sunshine painting in the provided photograph. Preserve character identity, facial features, exact pose, proportions, expression, colors, clothes, objects and visible paint style from the real reference. Remove only the photographed room, paper edges, easel, wall and table. Correct photographic perspective. Fill backdrop with near-black charcoal subtle painted texture. No extra objects, no invented subjects, no text, no borders, no watermark, not a sculpture.” Se precisó qué personaje corresponde a cada foto; para el bufón sólo el lienzo derecho y para el personaje violeta conservar el estilo de ilustración plana, sin realismo humano ni gafas añadidas.

**Pincelada:** “ONE elegant hand-painted antique gold ochre dry brush stroke, broad to tapered left to right, expressive imperfect edge, fine visible bristle striations and slight layered pigment, muted luminous antique gold #d8a74e. Mostly horizontal slightly ascending. No text, no letters, no background, genuine alpha transparency, no shadow, no checkerboard.”

**Textura:** “Near-black warm charcoal #11100e oil-painted linen canvas in raking very low light, minute irregular dry-brush pigment and fine canvas tooth, subtle soot and antique gold traces almost invisible at rightmost edge. Flat evenly dark, very low contrast. No objects, no characters, no light beams, no vignette, no text, no logos, no motivational writing.”

**Logo:** extracción del fondo blanco a alfa y cambio sólo de las letras negras a marfil #f1e8d4, conservando letra, espaciado, proporciones, irregularidades y sol dorado. Recorte de márgenes transparentes como transformación mecánica adicional.

**Favicon:** extraer únicamente el sol original del logo sobre alfa, sin letras ni fondo, centrarlo con margen y conservar su pincelada dorada.

## Entrega

`php scripts/prepare-image-assets.php` crea derivados WebP de las reproducciones y textura y recorta sólo márgenes alfa del logo/subrayado. No cambia el diseño de las obras. Los originales de referencia permanecen sin modificar en sus carpetas locales. `php artisan db:seed --class=DemoArtworkSeeder` crea sólo muestras locales, sin precio, medidas ni disponibilidad afirmada. No se ejecuta en producción. Mike debe revisar las reproducciones y sustituir/confirmar sus imágenes finales antes de vender.

## Ampliación del catálogo del 22 de octubre

- `public/images/art/arab-alchemist.png` y `.webp`: **Personaje de gorro moteado**, a partir de `Muestras de pinturas/WhatsApp Image 2026-10-19 at 9.18.52 PM.jpeg`. El nombre de archivo es legado: esta pintura se identificó erróneamente como El Árabe Alquimista y se corrigió tras la aclaración del usuario. Se conserva el gorro amarillo, dedo levantado, rostro, barba y ropa de la pintura real. Prompt original: reproducción frontal 4:5, fiel al personaje y a la pincelada; retirar papel/entorno fotografiados; fondo carbón; sin nuevos objetos, letras ni recortes de cabeza o mano.
- `public/images/sculptures/{wizard,cat,monk,jester}.png` y `.webp`: cuatro interpretaciones escultóricas conceptuales basadas en los respectivos PNG regenerados de las pinturas. No se presentan como fotografías de piezas físicas existentes. Se conserva el PNG maestro y se sirve WebP.

Prompt común para esculturas (herramienta integrada): “Use case: stylized-concept. Asset: portrait 4:5 product concept render for a sculpture catalogue. Reference image is Mike Sunshine's painted character. Translate THIS SAME character into a highly detailed hand-painted resin collectible bust, preserving facial identity, expression, exact silhouette, costume and palette. Clearly three-dimensional sculpted volume, visible brush-painted details on resin, realistic studio material response, warm raking key light, deep gentle shadows. Bust on a short dark bronze rectangular plinth; include entire cap/ears/topknot, hands or book if present, and whole plinth with at least 8 percent breathing room on all edges. Three-quarter front view, camera at eye level, near-black warm charcoal studio backdrop #11100e with a subtle textured floor. SINGLE bust ONLY. This is an imaginative sculpture concept based on the painting, not a photograph of an existing physical piece. No text, letters, watermark, logo, border, room, pedestal cropping or extra objects.” Se especificó el personaje y su ropa/paleta para cada referencia.

El seeder local añade ocho pinturas y cuatro conceptos 3D. Corrige sólo la identificación errónea conocida y conserva el ID, precios, existencias y contenido; respeta títulos/alt personalizados y reconoce imágenes ya existentes para evitar duplicarlas si cambió su slug. También completa sólo los campos vacíos con contacto ficticio (`.example`, teléfono 555-0100, ubicación por confirmar) y redes `/#`, según autorización del usuario. Todos se sustituyen desde administración antes de publicar.

## Composiciones de heroes y banners

Las ocho capas iniciales de `public/images/compositions/` se generaron con la herramienta integrada y alfa. Se guardan PNG maestros y derivados WebP. Sus referencias y prompts originales están en `docs/prompts-composiciones.json`; la mención al Alquimista en el prompt histórico del banner de Inicio fue una identificación errónea y corresponde al personaje del gorro moteado.

| Uso | Personaje |
| --- | --- |
| Hero Inicio | Mago pintado, capucha violeta |
| Hero Pinturas | Sabio de túnica verde |
| Hero Esculturas | Árabe Alquimista con turbante y frasco verde, concepto 3D; sustituye al gato |
| Hero Contacto | Bufón pintado |
| Banner Inicio | Personaje de gorro moteado |
| Banner Pinturas | Gato esfinge pintado |
| Banner Esculturas | Mago, concepto 3D |
| Banner Contacto | Mandril pintado |

Las instrucciones preservan personaje y paleta, añaden capas de pincel seco, impasto dorado, textura de lienzo y motas de pigmento, con margen para conservar la silueta. Los textos y controles siguen siendo HTML. No se incorporan frases motivacionales a la derecha de las imágenes. Los banners usan `object-fit: contain` y encuadres distintos a los antiguos recortes ampliados.

## Correcciones de identidad y transparencia

- `public/images/art/alchemist-turban.{png,webp}`: **El Árabe Alquimista**, exclusivamente el lienzo izquierdo de `Muestras de pinturas/WhatsApp Image 2026-10-19 at 9.18.52 PM (1).jpeg`. Turbante crema/gris con gema turquesa, barba y bigote oscuros, túnica azul/ocre y frasco de líquido verde. El bufón del lienzo derecho no se usa en esta representación. Ficha independiente disponible para solicitudes.
- `public/images/compositions/hero-sculptures-alchemist.{png,webp}`: interpretación escultórica conceptual de ese mismo lienzo izquierdo, sobre alfa, con capas de pinceladas y pigmento dorado. Conserva turbante, rostro, frasco y base dentro del encuadre. No acredita una escultura física existente.
- `public/images/compositions/artist-mandrill.{png,webp}`: edición con alfa de la representación del mandril; silueta completa y margen. Se muestra con `object-fit: contain`, sin el antiguo recorte ni sombra rectangular interior.

Los prompts y la cadena de edición de estos tres recursos están en `docs/prompts-ajustes.json`. Los banners existentes se reflejan con `scaleX(-1)` únicamente en la capa de imagen; texto y controles conservan su orientación. No se regeneraron para invertir la composición.

## Fondos y miniaturas de categorías

Correcciones realizadas con la herramienta integrada de imágenes, conservando la identidad, pose y estilo de los recursos anteriores. Prompts, referencias y revisión Git de los archivos usados como entrada: `docs/prompts-fondos.json`.

| Recurso PNG maestro y WebP | Corrección y uso |
| --- | --- |
| `public/images/art/alchemist-turban.{png,webp}` | Sustituye el fondo marfil por lienzo carbón, incluido el fondo visible a través del frasco. Mantiene personaje y ficha. |
| `public/images/compositions/hero-paintings-monk.{png,webp}` | Elimina los rellenos negros del fondo y conserva figura, pinceladas doradas y bordes con alfa. Se aplica tanto al catálogo de Pinturas como a su página de servicio. |
| `public/images/compositions/category-painting-cat.{png,webp}` | Recorte transparente del gato pintado, con orejas, cuello y borde inferior completos. Acceso a Pinturas en Inicio. |
| `public/images/compositions/category-sculpture-wizard.{png,webp}` | Recorte transparente del concepto escultórico del mago, conservando capucha, pluma, libro y base. Acceso a Esculturas en Inicio. |

El script `scripts/prepare-image-assets.php` entrega las miniaturas `category-*` a 320 × 400 px (aproximadamente 21 y 32 kB) y conserva alfa durante el remuestreo. Los PNG maestros mantienen su resolución original. Se comprobó alfa completamente transparente (127 en GD) en las esquinas y zonas vacías izquierdas de los tres PNG transparentes y sus WebP. Las miniaturas usan `object-fit: contain`.

## Retrato de Mike para Sobre mí

- Maestro: `public/images/artist/mike-portrait.png` (1122 × 1402 px).
- Entrega: `public/images/artist/mike-portrait.webp` (aproximadamente 208 kB), conservando alfa.
- Referencia: foto adjunta `codex-clipboard-8b996494-1064-41b9-a239-f8f6fb888634.png`.
- Herramienta: `image_gen` integrada, con edición de la fotografía suministrada. Se retiró el damero, se conservó la identidad y pose del retrato, se aplicó un tratamiento cálido sutil y se integró el borde inferior sobre alfa. No se generó un personaje que sustituya a Mike.
- Prompt completo y ruta de la referencia: `docs/prompt-retrato-mike.json`.

La página usa el campo `portrait_path` del estudio y el recurso inicial como respaldo si falta una foto. Una sustitución desde administración pasa por la validación raster y el servicio de imágenes existente; se almacena con un nombre UUID nuevo y no requiere regenerar la página. La composición editorial, las reglas, la órbita y los textos son HTML/CSS; el lienzo oscuro y la pincelada reutilizan los recursos gráficos existentes.
