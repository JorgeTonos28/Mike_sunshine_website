<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'privacidad' => ['Privacidad', 'Datos que se recogen', 'Al enviar una consulta o solicitud se recogen los datos que facilitas: nombre, correo, teléfono opcional, mensaje y dirección cuando solicitas envío. Se utilizan para gestionar tu solicitud y contactar contigo.', 'Tratamiento y proveedores', 'El panel restringe los datos de clientes a administradores. El servicio utiliza alojamiento, base de datos y correo electrónico. La identidad del responsable, proveedores concretos, conservación y canal para ejercer derechos están pendientes de confirmación.'],
            'terminos' => ['Términos y condiciones', 'Cómo funciona una solicitud', 'El carrito permite reunir obras y solicitar información para adquirirlas. Enviar el formulario no realiza un cobro, no reserva automáticamente una pieza ni confirma una compraventa. Mike debe confirmar disponibilidad, importe y condiciones de entrega.', 'Antes de confirmar', 'El precio final, envío, plazos, cancelaciones, devoluciones, encargos y uso de imágenes deberán acordarse y documentarse antes de confirmar la compra. Las representaciones digitales pueden diferir de la obra física.'],
            'cookies' => ['Cookies', 'Cookies necesarias', 'La web utiliza una cookie de sesión y una protección CSRF para mantener el carrito, proteger formularios y permitir el acceso al panel. Son necesarias para estas funciones.', 'Cookies opcionales', 'Esta versión no incorpora analítica, publicidad, píxeles de seguimiento ni contenidos sociales embebidos. Los enlaces a redes abren servicios externos. Si se incorporan tecnologías opcionales, esta información y sus controles deberán actualizarse antes de activarlas.'],
        ];
        foreach ($pages as $slug => [$title, $h1, $p1, $h2, $p2]) {
            LegalPage::firstOrCreate(['slug' => $slug], ['title' => $title, 'content_blocks' => [
                ['type' => 'heading', 'text' => $h1], ['type' => 'paragraph', 'text' => $p1],
                ['type' => 'heading', 'text' => $h2], ['type' => 'paragraph', 'text' => $p2],
            ]]);
        }
    }
}
