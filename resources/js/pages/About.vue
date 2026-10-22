<script setup lang="ts">
import { computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhArrowDown,
    PhArrowRight,
    PhArrowUpRight,
    PhWaveform,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import SocialLinks from "../components/SocialLinks.vue";
import type { ArtworkImage, Shared } from "../types";

defineProps<{
    works: {
        id: number;
        title: string;
        slug: string;
        image: ArtworkImage | null;
    }[];
}>();
const page = usePage<Shared>();
const studio = computed(() => page.props.studio);
const portrait = computed(
    () => studio.value.portrait_path || "/images/artist/mike-portrait.webp",
);
const languages = [
    {
        number: "01",
        name: "Música",
        note: "El sonido también cuenta historias.",
        text: "Una parte esencial del universo de Mike. El ritmo, la atmósfera y la emoción comparten espacio con su búsqueda visual.",
        image: null,
        href: "/contacto",
        link: "Conversemos",
    },
    {
        number: "02",
        name: "Pintura",
        note: "Una mirada que se queda en el lienzo.",
        text: "Personajes conocidos, retratos y creaciones propias. Rostros que invitan a detenerse en una expresión, una textura o un detalle.",
        image: "/images/compositions/category-painting-cat.webp",
        href: "/pinturas",
        link: "Ver pinturas",
    },
    {
        number: "03",
        name: "Escultura",
        note: "La imaginación ocupa su espacio.",
        text: "Otra manera de acercarse a un personaje: explorar su volumen, su presencia y las posibilidades de llevarlo a tres dimensiones.",
        image: "/images/compositions/category-sculpture-wizard.webp",
        href: "/esculturas",
        link: "Ver esculturas",
    },
];
const process = [
    {
        title: "Encontrar una idea",
        text: "Una imagen, una referencia o una conversación pueden abrir la puerta. Lo primero es descubrir qué historia merece tomar forma.",
    },
    {
        title: "Buscar su carácter",
        text: "Explorar la expresión, las proporciones y la atmósfera. Encontrar esos detalles que hacen que un personaje se sienta propio.",
    },
    {
        title: "Trabajar la materia",
        text: "Dar espacio al color, la textura y el volumen. Dejar que la pieza encuentre su lenguaje en los detalles.",
    },
    {
        title: "Compartir la historia",
        text: "Una obra abre otra conversación cuando alguien la descubre. El proceso continúa en esa nueva mirada.",
    },
];
</script>
<template>
    <Head title="Sobre mí">
        <meta
            name="description"
            content="Conoce el universo creativo de Mike Sunshine: música, pintura, escultura y las historias detrás de su mirada."
        />
        <link rel="preload" as="image" :href="portrait" />
    </Head>
    <StoreLayout>
        <div class="about-page">
            <section
                id="el-artista"
                class="about-cover container"
                aria-labelledby="about-title"
            >
                <div class="about-cover-topline">
                    <p class="eyebrow">Sobre el artista</p>
                    <span>Sonido · Color · Volumen</span>
                </div>
                <h1 id="about-title">Una mirada.<em>Muchas formas.</em></h1>
                <div class="about-cover-grid">
                    <figure class="about-portrait">
                        <div class="about-portrait-frame">
                            <span
                                class="about-portrait-orbit"
                                aria-hidden="true"
                            ></span>
                            <img
                                :src="portrait"
                                :alt="`Retrato de ${studio.name}`"
                                width="1122"
                                height="1402"
                                fetchpriority="high"
                            />
                        </div>
                        <figcaption>
                            <span>{{ studio.name }}</span
                            ><span>El artista detrás de la obra</span>
                        </figcaption>
                    </figure>
                    <div class="about-introduction">
                        <p class="eyebrow">El punto de encuentro</p>
                        <h2>
                            {{ studio.name
                            }}<span>Un universo en movimiento.</span>
                        </h2>
                        <p class="about-intro-lead">
                            La misma curiosidad.<br />Distintos lenguajes.
                        </p>
                        <p v-if="studio.bio" class="about-bio">
                            {{ studio.bio }}
                        </p>
                        <p>
                            Hay personajes que nacen de una imagen conocida y
                            otros que pertenecen a su propio universo. Esta es
                            una invitación a conocer esa mirada, más allá de la
                            pieza terminada.
                        </p>
                        <a href="#lenguajes" class="text-link"
                            >Entra en mi universo <PhArrowDown :size="18"
                        /></a>
                    </div>
                </div>
                <nav class="about-chapters" aria-label="En esta página">
                    <a href="#el-artista">El artista</a
                    ><a href="#lenguajes">Sus lenguajes</a
                    ><a href="#proceso">El proceso</a
                    ><a v-if="works.length" href="#el-estudio">Las obras</a>
                </nav>
            </section>

            <section class="about-manifesto">
                <div class="container about-manifesto-inner">
                    <p class="eyebrow">Una forma de mirar</p>
                    <div>
                        <h2>
                            Cambiar de lenguaje.<br /><em
                                >Conservar la mirada.</em
                            >
                        </h2>
                        <p>
                            Una melodía, un rostro pintado, un personaje con
                            volumen. El medio cambia; la intención de contar
                            algo permanece.
                        </p>
                    </div>
                </div>
            </section>

            <section
                id="lenguajes"
                class="container about-languages"
                aria-labelledby="languages-title"
            >
                <div class="about-section-heading">
                    <p class="eyebrow">Tres maneras de crear</p>
                    <h2 id="languages-title">
                        Donde se encuentran<br />mis mundos.
                    </h2>
                </div>
                <article
                    v-for="language in languages"
                    :key="language.number"
                    class="about-language-row"
                >
                    <span class="about-index">{{ language.number }}</span>
                    <div class="about-language-name">
                        <img
                            v-if="language.image"
                            :src="language.image"
                            alt=""
                            width="320"
                            height="400"
                            loading="lazy"
                        />
                        <PhWaveform
                            v-else
                            :size="64"
                            weight="thin"
                            aria-hidden="true"
                        />
                        <div>
                            <h3>{{ language.name }}</h3>
                            <p>{{ language.note }}</p>
                        </div>
                    </div>
                    <p class="about-language-description">
                        {{ language.text }}
                    </p>
                    <Link :href="language.href" class="about-language-link"
                        >{{ language.link }}<PhArrowUpRight :size="20"
                    /></Link>
                </article>
            </section>

            <section
                id="proceso"
                class="about-process"
                aria-labelledby="process-title"
            >
                <div class="container about-process-inner">
                    <div class="about-process-heading">
                        <p class="eyebrow">Entre la idea y la pieza</p>
                        <h2 id="process-title">
                            Lo que no siempre<br />se ve.
                        </h2>
                        <p>
                            Detrás de cada resultado hay una búsqueda. Este es
                            un vistazo a la conversación entre la imaginación y
                            el hacer.
                        </p>
                        <img
                            src="/images/design/brush-gold.png"
                            alt=""
                            class="about-process-brush"
                            width="240"
                            height="30"
                            loading="lazy"
                        />
                    </div>
                    <ol class="about-process-steps">
                        <li v-for="(step, index) in process" :key="step.title">
                            <span class="about-index">0{{ index + 1 }}</span>
                            <div>
                                <h3>{{ step.title }}</h3>
                                <p>{{ step.text }}</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </section>

            <section
                v-if="works.length"
                id="el-estudio"
                class="container about-studio"
                aria-labelledby="studio-title"
            >
                <div class="about-section-heading about-studio-heading">
                    <div>
                        <p class="eyebrow">Una ventana al universo</p>
                        <h2 id="studio-title">
                            Cada personaje,<br /><em
                                >una historia por descubrir.</em
                            >
                        </h2>
                    </div>
                    <Link href="/pinturas" class="text-link"
                        >Explorar las obras <PhArrowRight :size="18"
                    /></Link>
                </div>
                <div class="about-gallery">
                    <Link
                        v-for="work in works"
                        :key="work.id"
                        class="about-work"
                        :href="`/obras/${work.slug}`"
                        :aria-label="`Conocer ${work.title}`"
                    >
                        <figure>
                            <img
                                v-if="work.image"
                                :src="work.image.path"
                                :alt="work.image.alt"
                                width="800"
                                height="1000"
                                loading="lazy"
                            />
                            <figcaption>
                                <span>{{ work.title }}</span
                                ><PhArrowUpRight :size="20" />
                            </figcaption>
                        </figure>
                    </Link>
                </div>
            </section>

            <section class="about-invitation">
                <div class="container about-invitation-inner">
                    <p class="eyebrow">La próxima historia</p>
                    <h2>Tal vez empiece<br /><em>con tu idea.</em></h2>
                    <p>
                        Un personaje, un retrato, una pieza especial o una
                        colaboración. Cuéntame lo que imaginas y conversemos
                        sobre cómo darle forma.
                    </p>
                    <div class="about-invitation-actions">
                        <Link
                            href="/servicios/encargos-personalizados"
                            class="button gold"
                            >Hablemos de un encargo
                            <PhArrowRight :size="19" /></Link
                        ><Link href="/contacto" class="text-link"
                            >Contactar al estudio <PhArrowUpRight :size="18"
                        /></Link>
                    </div>
                    <SocialLinks labels />
                </div>
            </section>
        </div>
    </StoreLayout>
</template>
