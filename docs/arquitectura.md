# Decisiones iniciales

Referencia revisada: Vamo al game, rama main, 21 de octubre de 2026. Archivos: composer.json, package.json, AGENTS.md, SecurityHeaders, GoogleAuthController, SynchronizeGoogleUser, FortifyServiceProvider y SecurityHardeningTest.

## Qué se adopta

Laravel 13, Inertia/Vue 3/TypeScript, Vite/Tailwind 4; FormRequests, Policies/Gates, Actions, relaciones con claves foráneas, pruebas de regresión, CSP con nonce y HSTS en HTTPS, throttling y sesiones del servidor. Google mediante Socialite stateful. Se restringe a cuentas que un administrador haya creado previamente, con verificación de email del proveedor y control de ID vinculado.

## Qué se excluye

Auth0/Caona, módulos deportivos, app móvil, API móvil, contratos compartidos y pagos online. La web sí debe ser responsive.

## Unidades de entrega

1. Base Laravel, herramientas, configuración e instrucciones.
2. Persistencia de catálogo, configuración, usuarios, pedidos y bloques de contenido.
3. Acceso local/Google y autorización.
4. Catálogo administrable, subidas y ajustes del negocio.
5. Carrito de sesión, solicitudes, correo con reintentos y seguimiento privado.
6. Assets generados con procedencia registrada y UI pública oscura.
7. UI administrativa, políticas editables y cuestionario del negocio.
8. Pruebas de seguridad y flujos, compilación y revisión visual.

## Diseño

Galería de artista y catálogo de compra por solicitud, con lenguaje de pintura al óleo, fondos carbón y acento dorado del logo. Tipografía serif por fidelidad explícita al mockup, interfaz sans. DESIGN_VARIANCE=7, MOTION_INTENSITY=3, VISUAL_DENSITY=4. Tema oscuro constante, controles rectangulares con radio pequeño. Z-index: contenido 0, navegación 20, avisos 30, diálogos 50. Animaciones cortas de feedback con reduced-motion.

No se recrea el marinero del mockup porque no aparece como obra en las muestras. El hero se deriva del mago de capucha violeta documentado. Las imágenes del catálogo son representaciones regeneradas, por confirmar por Mike antes de venta. Los nombres descriptivos de muestra son provisionales; precios y medidas no se inventan.

## Pedido

El servidor valida productos publicados y disponibles, toma sus datos vigentes, guarda pedido e items y agenda una notificación durable en la misma transacción. Un UUID único por formulario evita duplicados por reintento. El carrito no reserva stock. Estado inicial: recibido; Mike confirma precio y envío antes de cerrar la venta. Los clientes no necesitan cuenta. Datos privados cifrados en reposo y limitados al administrador.

## Legal

Privacidad, términos y cookies tienen diseño y edición. Su texto parte de la funcionalidad técnica implementada y queda marcado como provisional hasta recibir las respuestas del cuestionario y la revisión correspondiente. No se activan analítica, publicidad ni cookies opcionales.
