<?php

namespace App\Support;

class StudioServices
{
    public static function all(): array
    {
        return [
            [
                'slug' => 'pinturas', 'title' => 'Pinturas', 'eyebrow' => 'Tu historia sobre el lienzo', 'subject' => 'painting',
                'intro' => 'Retratos, personajes y mundos imaginarios. Una pintura que empiece con tu idea y encuentre su propia voz.',
                'description' => 'Podemos conversar sobre un retrato, un personaje o una escena original. Las referencias nos ayudan a definir el estilo, el formato y los detalles de la obra antes de empezar.',
                'image' => '/images/art/neon.webp', 'hero' => '/images/compositions/hero-paintings-monk.webp', 'cta' => 'Solicitar una pintura',
                'message' => 'Hola Mike, me gustaría solicitar una pintura personalizada. Quisiera conversar sobre el personaje o tema, las referencias, el formato y el presupuesto para mi obra.',
                'details' => ['El personaje, retrato o escena que imaginas.', 'Las referencias y el estilo que te gustaría explorar.', 'El formato, los materiales y el lugar donde vivirá la pieza.', 'El presupuesto y la fecha que tienes en mente.'],
            ],
            [
                'slug' => 'esculturas-3d', 'title' => 'Esculturas 3D', 'eyebrow' => 'Del dibujo al volumen', 'subject' => 'sculpture',
                'intro' => 'Personajes que cobran vida fuera del lienzo. Exploremos una pieza con volumen, textura y carácter.',
                'description' => 'Partimos de una idea o un personaje para conversar sobre su interpretación en tres dimensiones. El modelado, la fabricación y el acabado se definen con Mike según las posibilidades de cada proyecto.',
                'image' => '/images/sculptures/wizard.webp', 'hero' => '/images/compositions/hero-sculptures-cat.webp', 'cta' => 'Solicitar una escultura',
                'message' => 'Hola Mike, me interesa una escultura 3D por encargo. Me gustaría definir contigo el personaje, el tamaño, el acabado y las posibilidades de producción de la pieza.',
                'details' => ['El personaje y sus referencias visuales.', 'La pose, el tamaño y la base de la escultura.', 'El material y el acabado que se puedan realizar.', 'Las unidades, el presupuesto y la entrega.'],
            ],
            [
                'slug' => 'comisiones-personalizadas', 'title' => 'Comisiones personalizadas', 'eyebrow' => 'Algo que sólo tú podrías imaginar', 'subject' => 'commission',
                'intro' => 'Personajes, personas, mascotas, ideas propias o regalos únicos. Cuéntame lo que tienes en mente.',
                'description' => 'Una comisión es una conversación entre tu idea y el universo creativo de Mike. Podemos explorar una pintura, una escultura o una propuesta que combine distintas maneras de crear.',
                'image' => '/images/art/cat.webp', 'hero' => '/images/compositions/banner-home-arab.webp', 'cta' => 'Hablemos de tu idea',
                'message' => 'Hola Mike, tengo una idea para una comisión personalizada. Me gustaría contarte el concepto y definir contigo si podemos convertirlo en una pintura, una escultura o una pieza especial.',
                'details' => ['La historia y la intención detrás de tu idea.', 'Las personas, personajes o mascotas que quieres representar.', 'El tipo de pieza y las referencias que te inspiran.', 'La ocasión, el presupuesto y la fecha deseada.'],
            ],
            [
                'slug' => 'eventos-colaboraciones', 'title' => 'Eventos y colaboraciones', 'eyebrow' => 'El arte también nos reúne', 'subject' => 'collaboration',
                'intro' => 'Exposiciones, murales, live painting, colaboraciones con marcas y proyectos culturales. Conversemos sobre tu propuesta.',
                'description' => 'Si estás preparando una exposición, un encuentro o un proyecto creativo, comparte tu visión. Mike revisará la propuesta, su alcance y su disponibilidad para acordar una posible colaboración.',
                'image' => '/images/art/arab-alchemist.webp', 'hero' => '/images/compositions/banner-contact-mandrill.webp', 'cta' => 'Proponer un proyecto',
                'message' => 'Hola Mike, quisiera proponerte un evento o una colaboración artística. Me gustaría compartir el concepto, la ubicación, las fechas, el alcance y el presupuesto del proyecto.',
                'details' => ['El concepto y quién organiza el proyecto.', 'La ciudad, el espacio y las fechas previstas.', 'La participación artística y los entregables que imaginas.', 'El presupuesto, la logística y los colaboradores.'],
            ],
        ];
    }
}
