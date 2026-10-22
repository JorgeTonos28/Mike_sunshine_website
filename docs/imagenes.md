# Recursos gráficos

Generados con la herramienta integrada de imágenes, usando las fotos suministradas como referencia de contenido. No se han usado fotografías de stock ni se han inventado pinturas o esculturas para el catálogo.

| Archivo final | Referencia de contenido | Uso |
| --- | --- | --- |
| `public/images/art/hero-wizard.png` | `Muestras de pinturas/WhatsApp Image 2026-10-19 at 9.18.51 PM (1).jpeg` | Composición de hero con alfa, mago violeta. MSW1 se usó únicamente como referencia de tratamiento pictórico. |
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
