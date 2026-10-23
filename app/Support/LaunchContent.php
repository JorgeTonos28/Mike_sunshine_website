<?php

namespace App\Support;

class LaunchContent
{
    public const KEY = 'launch-2026-10-23-v1';

    public static function settings(): array
    {
        return [
            'legal_name' => 'Michael Febles Quezada',
            'contact_email' => 'mikedasunshine@gmail.com',
            'phone' => '+1 849-428-8682',
            'address' => 'No. 4-A, Edificio B-1, calle Jacinto de la Concha, sector Villa Progreso del Este, Santo Domingo Este, República Dominicana.',
            'instagram_url' => 'https://www.instagram.com/mikesunshinegoat/',
            'facebook_url' => null, 'youtube_url' => null,
        ];
    }

    public static function pages(): array
    {
        return [
            'privacidad' => ['title' => 'Política de privacidad', 'content_blocks' => self::blocks([
                'Quién es responsable de tus datos' => 'Mike Sunshine es el nombre artístico y comercial de Michael Febles Quezada, artista que opera desde Santo Domingo Este, República Dominicana. Sus datos de contacto y domicilio figuran en esta página. Esta política explica cómo se utiliza la información al visitar el sitio, enviar una consulta o solicitar una obra.',
                'Qué información se recoge' => 'Recogemos tu nombre, correo electrónico, teléfono cuando lo facilitas y el contenido de tus mensajes. Una solicitud puede incluir las obras y cantidades elegidas, la forma de entrega y la dirección necesaria para cotizar o realizar un envío. Conservamos la referencia, el estado de la solicitud y las comunicaciones relacionadas. La web no recoge números de tarjeta ni credenciales bancarias. Evita incluir documentos de identidad, contraseñas o información sensible en los mensajes.',
                'Para qué se utiliza' => [
                    'Responder consultas, preparar cotizaciones y comprobar la disponibilidad de prints y esculturas.',
                    'Gestionar solicitudes, documentar los acuerdos y coordinar preparación, entrega y atención posterior.',
                    'Atender reclamaciones, solicitudes sobre tus datos y las obligaciones legales aplicables.',
                    'Mantener el carrito, proteger formularios y detectar accesos indebidos, errores o abuso del sitio.',
                ],
                'Tu decisión al enviar información' => 'Los formularios solicitan que hayas leído esta política antes de enviarlos. Los datos marcados como necesarios permiten atender la solicitud; los demás son opcionales. Puedes retirar tu consentimiento para actuaciones basadas en él, sin afectar lo realizado legítimamente con anterioridad ni las obligaciones de conservación que correspondan. No se utilizan los datos de los formularios para suscribirte a publicidad ni a un boletín.',
                'Quién puede acceder y con quién se comparte' => 'El artista y las personas autorizadas para administrar solicitudes pueden consultar los datos de clientes; el rol de editor del catálogo no tiene acceso a ellos. El alojamiento, la base de datos, el correo y las copias de seguridad permiten operar el servicio. Cuando se acuerde una entrega, se facilitarán al transportista únicamente los datos necesarios para realizarla. También podrá comunicarse información a asesores o autoridades cuando sea necesario para cumplir obligaciones legales o atender una reclamación. No vendemos tus datos ni los cedemos para publicidad de terceros.',
                'Correo, redes y proveedores externos' => 'Los avisos de nuevas solicitudes se envían al buzón de órdenes del dominio y remiten al panel protegido. Las conversaciones posteriores pueden gestionarse por el correo público del artista. Los enlaces a Instagram, WhatsApp u otros servicios externos sólo se abren cuando los eliges y esos servicios aplican sus propias políticas; no se insertan feeds, mapas, chats ni reproductores de terceros automáticamente. La autenticación con Google, cuando esté habilitada, se reserva a usuarios autorizados del panel y no es necesaria para comprar. Estos proveedores pueden tratar información fuera de República Dominicana; puedes pedir información sobre los servicios que intervienen en tu solicitud.',
                'Durante cuánto tiempo se conserva' => 'La información se conserva mientras resulte necesaria para responder y dar seguimiento a la consulta o solicitud. Los datos de compras, pagos y reclamaciones se mantienen durante los períodos exigibles para justificar las operaciones, atender garantías y cumplir obligaciones legales. Una solicitud en espera puede conservarse para avisarte de su disponibilidad; puedes pedir que se cierre y se suprima la información que ya no sea necesaria. Las copias de seguridad pueden mantener información hasta su sustitución o eliminación dentro de su ciclo de conservación. No se establece una conservación indefinida para finalidades ajenas a tu solicitud.',
                'Cómo ejercer tus derechos' => 'Puedes solicitar acceso, rectificación, actualización o supresión de tus datos, así como plantear una oposición o retirar un consentimiento cuando proceda, escribiendo al correo público indicado en esta página. Indica tu nombre, el correo utilizado y, si existe, la referencia de la solicitud. Se pedirá sólo la información razonablemente necesaria para comprobar tu identidad. Si es preciso conservar parte de los datos por una obligación legal o una reclamación pendiente, se te explicará el motivo y se limitará su uso.',
                'Seguridad, datos técnicos y menores' => 'La aplicación restringe el acceso al panel, cifra datos personales de solicitudes y utiliza medidas de protección de formularios y sesiones. El servidor puede registrar dirección IP, navegador, fechas y errores por razones de funcionamiento y seguridad. Ningún sistema elimina todos los riesgos: comunícanos cualquier incidencia por los canales del estudio. Las compras y encargos se dirigen a personas mayores de edad; si una persona menor desea realizar uno, debe intervenir su representante. No se solicita fecha de nacimiento por defecto.',
                'Cambios en esta política' => 'La fecha y versión publicadas identifican el texto vigente. Si cambian los usos de la información, se actualizará esta página y se recabará una nueva autorización cuando corresponda. Las peticiones sobre privacidad se atenderán conforme a la normativa aplicable en República Dominicana, incluida la Ley 172-13.',
            ])],
            'terminos' => ['title' => 'Términos y condiciones', 'content_blocks' => self::blocks([
                'El estudio y el catálogo' => 'Mike Sunshine es el proyecto artístico de Michael Febles Quezada, con domicilio y contacto indicados en esta página. El catálogo permite descubrir su obra y solicitar prints o esculturas por encargo. Para realizar una compra se requiere capacidad legal para contratar; las personas menores de edad deben actuar mediante su representante.',
                'Qué se ofrece' => 'Las pinturas se ofrecen en forma de prints: reproducciones de obras pintadas por Mike. El formato inicial es de 16 × 20 pulgadas, con firma y marco; los detalles concretos de la impresión y del marco se confirman en la ficha o en la cotización. Comprar un print no equivale a adquirir la pintura original. Las esculturas se realizan por pedido y después de una cotización. Las imágenes identificadas como conceptos 3D exploran una posible pieza y no acreditan la existencia de una escultura física en inventario.',
                'Cómo funciona la solicitud' => [
                    'Puedes reunir obras y cantidades en el carrito y enviar tus datos mediante el formulario.',
                    'Enviar una solicitud no produce un cobro, no reserva existencias y no confirma por sí solo una compraventa.',
                    'El estudio revisa disponibilidad, características, precio y entrega, y te envía las condiciones para tu aceptación.',
                    'Antes de pagar deben quedar por escrito la pieza acordada, el total, la forma de pago, la entrega y cualquier condición particular del encargo.',
                ],
                'Disponibilidad y solicitudes en espera' => 'Es posible solicitar una obra aunque no haya unidades disponibles. En ese caso se registra una petición en espera y el estudio se comunicará contigo cuando pueda atenderla. Esta petición no garantiza stock, un turno de producción ni un precio futuro. La disponibilidad y cualquier variación de la cotización se comunicarán antes de que decidas confirmar. Puedes pedir que se cierre una solicitud pendiente por los canales de contacto.',
                'Precios, cotizaciones y pago' => 'Cuando una ficha no muestre precio, el importe se facilita mediante cotización. Si muestra uno, se interpreta en la moneda indicada y para las características descritas. El coste de transporte y los conceptos adicionales que correspondan se detallarán antes de tu aceptación; no se aplicarán cargos que no hayas aceptado. La web no tiene una pasarela de pago: el estudio comunica de forma privada los medios e instrucciones disponibles. Los anticipos, saldos y vigencia de una cotización, cuando existan, se documentarán expresamente para ese pedido.',
                'Preparación y entrega' => 'El destino, la modalidad de entrega o recogida, los costes y los plazos se acuerdan para cada solicitud. No se promete cobertura de envío universal ni un plazo único para prints y esculturas. Cualquier estimación se comunica antes de confirmar y los cambios relevantes se informan al cliente. Facilita una dirección y datos de contacto correctos. No envíes devoluciones ni acudas al domicilio sin coordinar antes con el estudio.',
                'Encargos y representaciones de las obras' => 'Para una escultura o trabajo personalizado se concretan diseño, materiales, medidas, acabado, revisiones, precio y calendario antes de producir. Los cambios posteriores requieren aceptación de las condiciones nuevas. El color puede variar entre pantallas e impresión; el encuadre de una imagen digital o un concepto no sustituye las características acordadas. Estas variaciones de presentación no justifican entregar una pieza distinta de lo pactado ni limitan tus derechos ante defectos.',
                'Cancelaciones y cambios de opinión' => 'Una consulta o solicitud aún no confirmada puede cancelarse sin cargo. Para una compra confirmada o un encargo iniciado, comunícate con el estudio para revisar su estado y las condiciones particulares previamente aceptadas, siempre con respeto a los derechos legales del consumidor. No se presume que un anticipo sea no reembolsable ni se aplican penalizaciones o exclusiones generales de devolución por el mero uso de esta web.',
                'Daños, errores y reclamaciones' => 'Si recibes una pieza dañada, equivocada o que no se ajusta a lo acordado, contacta al estudio con la referencia de compra, una descripción y, cuando sea posible, fotografías del producto y embalaje. Conserva los elementos que permitan revisar la incidencia y coordina cualquier devolución. La solución que corresponda —como reparación, sustitución, ajuste del precio o reembolso— se determinará según el caso y la ley aplicable. Ninguna condición particular elimina garantías o derechos irrenunciables del consumidor.',
                'Derechos sobre las imágenes' => 'La adquisición de un print o una escultura no transmite derechos de reproducción, distribución comercial ni explotación digital de la obra. Los derechos de personajes, marcas y referencias de terceros corresponden a sus titulares; la presentación del catálogo no implica una licencia para el comprador. Si aportas fotografías o referencias para un encargo, debes contar con la autorización necesaria. El estudio podrá rechazar o ajustar trabajos cuando no puedan realizarse con los derechos que correspondan.',
                'Contacto, legislación y cambios' => 'Las consultas y reclamaciones se atienden a través del correo y teléfono publicados en esta página. Son aplicables las normas de República Dominicana, incluida la Ley 358-05 de protección del consumidor, sin excluir los derechos obligatorios que correspondan al comprador. Puedes acudir a Pro Consumidor u otras autoridades competentes cuando proceda. Las actualizaciones se publican con fecha y versión; no modifican retroactivamente un pedido confirmado sin el acuerdo o fundamento legal que corresponda.',
            ])],
            'cookies' => ['title' => 'Política de cookies', 'content_blocks' => self::blocks([
                'Qué son y para qué se usan' => 'Las cookies son pequeños archivos que el navegador guarda al visitar un sitio. Esta web utiliza únicamente las necesarias para mantener la sesión, recordar temporalmente el carrito, proteger formularios y permitir el acceso al panel del estudio. No es necesario crear una cuenta de cliente para enviar una solicitud.',
                'Cookies necesarias de esta web' => [
                    'Sesión del sitio: identifica tu sesión y mantiene el carrito y los pasos de los formularios. La configuración inicial caduca tras dos horas de inactividad; el uso continuado puede renovar esa duración.',
                    'Protección de formularios (XSRF-TOKEN): ayuda a comprobar que las solicitudes se realizan desde la sesión correcta. Su duración se vincula a la de la sesión.',
                    'Recordar acceso: sólo para usuarios del panel que activen «Recordarme». Permite mantener ese acceso entre visitas; el límite inicial del sistema es de hasta 400 días. Cerrar sesión invalida ese acceso persistente.',
                ],
                'Analítica, publicidad y almacenamiento local' => 'No se cargan herramientas de analítica, píxeles publicitarios, remarketing ni cookies de seguimiento comercial. El efecto de pincel y los controles visuales no realizan seguimiento: su preferencia se mantiene en memoria durante la visita, sin cookies ni almacenamiento local persistente. Si se incorporan servicios opcionales, se informará sobre ellos y se habilitarán los controles de consentimiento que correspondan antes de cargarlos.',
                'Instagram, WhatsApp y Google' => 'Instagram y WhatsApp se ofrecen como enlaces: visitar el sitio no carga sus widgets ni inicia una sesión con ellos. Al abrirlos sales a un servicio externo que utiliza su propia política. Google sólo interviene si una persona autorizada decide utilizarlo para entrar al panel, cuando esa opción esté habilitada; no es necesario para consultar el catálogo ni solicitar una obra.',
                'Cómo gestionar las cookies' => 'Puedes consultar, bloquear o borrar las cookies desde la configuración de tu navegador. Bloquear las necesarias puede impedir conservar el carrito, enviar formularios o acceder al panel. Para retirar el acceso persistente del estudio, cierra sesión y elimina las cookies del sitio si lo deseas. Los navegadores ofrecen instrucciones en sus secciones de privacidad y seguridad.',
                'Contacto y actualizaciones' => 'Puedes dirigir cualquier consulta sobre estas tecnologías al contacto público indicado en esta página. La fecha y versión identifican el texto vigente. Los datos vinculados a las cookies se tratan conforme a la Política de privacidad.',
            ])],
        ];
    }

    private static function blocks(array $sections): array
    {
        $blocks = [];
        foreach ($sections as $title => $content) {
            $blocks[] = ['type' => 'heading', 'text' => $title];
            $blocks[] = is_array($content) ? ['type' => 'bullets', 'items' => $content] : ['type' => 'paragraph', 'text' => $content];
        }

        return $blocks;
    }
}
