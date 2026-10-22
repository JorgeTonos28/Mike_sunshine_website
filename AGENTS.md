# Mike Sunshine: guía de desarrollo

## Alcance y referencias

- Proyecto web responsive. Stack: Laravel 13, Inertia, Vue 3, TypeScript, Vite y Tailwind 4.
- Ingeniería de referencia: https://github.com/JorgeTonos28/Vamo_al_game. Adaptar patrones al dominio del artista; no copiar sus módulos deportivos ni dependencias ajenas al alcance.
- Autenticación Laravel y Google mediante Socialite. Sin Auth0, aplicación móvil paralela ni pasarela de pago.
- Referencia visual: `Mockups/MSW1.png`; el resto aporta estructura con el tema oscuro del primero.
- No inventar obras, precios, dimensiones, disponibilidad, datos biográficos o políticas comerciales. Toda representación de una pintura debe derivarse de una referencia real de Mike. Esculturas vacías hasta recibir referencias.

## Arquitectura y seguridad

- Controladores finos; reglas de negocio en Actions/Services, validaciones en FormRequests y autorización en Policies/Gates/middleware.
- Validar y autorizar siempre en servidor. Compartir únicamente props de Inertia necesarias. Nunca aceptar precios, roles ni estados internos desde formularios públicos.
- Migraciones, constraints, modelos, casts, factories, seeders y pruebas deben mantenerse coherentes. `migrate --seed` debe funcionar en instalación limpia.
- CSRF, cookies HttpOnly/SameSite, rotación de sesión, throttling, contraseñas hash y cabeceras CSP. Ningún secreto, token OAuth, dato privado de clientes o contraseña en repositorio o logs.
- Registro público cerrado. Sólo administradores crean usuarios. Los editores gestionan obras; no usuarios, ajustes ni datos de clientes.
- Google con state de sesión, correo verificado y coincidencia de identidad de usuario autorizado. No usar `stateless()` ni desactivar TLS.
- Subidas limitadas a imágenes raster comprobadas por contenido y tamaño; nombres aleatorios. No SVG/HTML ejecutable. Rich content estructurado y validado, nunca HTML arbitrario.
- Los pedidos son solicitudes, no cobros ni reservas automáticas. Persistir una instantánea de productos y condiciones; correo en cola, reintentable, sin perder solicitudes si falla SMTP.
- Importes en unidades menores enteras; no float en reglas monetarias. No mostrar datos de clientes en páginas públicas ni URLs predecibles.

## Calidad y Git

- Identificar unidades lógicas antes de implementar. Validar y crear un commit pequeño y atómico al completar cada unidad, antes de continuar con la siguiente. Mensajes breves; sin squash salvo solicitud explícita.
- Preservar cambios del usuario y archivos de referencia. No incluir originales pesados por accidente; versionar sólo assets finales necesarios.
- Actualizar README cuando cambien setup, flujos, configuración o funcionalidades.
- Pruebas relevantes de autorización, OAuth, validación, integridad y pedidos; typecheck, build y formato. Revisar UI de escritorio y tamaños reducidos con evidencia visual.
- Texturas, composiciones y pinceladas se generan con imágenes; controles y textos se implementan en código. Mantener el logo y omitir frases decorativas de las esquinas.
