<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhPenNib,
    PhUsersThree,
    PhHeart,
} from "@phosphor-icons/vue";
const props = withDefaults(
    defineProps<{ variant?: "home" | "painting" | "sculpture" | "contact" }>(),
    { variant: "home" },
);
const emit = defineEmits<{ request: [] }>();
const banners = {
    home: {
        eyebrow: "Disponible para comisiones",
        title: "¿Una pieza a tu medida?",
        text: "Un personaje, un retrato o algo que todavía no existe. Cuéntame lo que imaginas.",
        button: "Hablemos",
        href: "/servicios/comisiones-personalizadas",
        image: "banner-home-arab",
        features: [
            "Ideas personalizadas",
            "Proceso compartido",
            "Piezas con alma",
        ],
    },
    painting: {
        eyebrow: "Arte sin fronteras",
        title: "¿Buscas una pieza personalizada?",
        text: "Cuéntame tu idea y creemos un personaje, retrato o escena única, hecha especialmente para ti.",
        button: "Hablemos de tu pieza",
        href: "/servicios/pinturas",
        image: "banner-paintings-cat",
        features: [
            "Tu imaginación",
            "Asesoría creativa",
            "Una historia propia",
        ],
    },
    sculpture: {
        eyebrow: "Personajes a tu medida",
        title: "¿Tienes una idea en mente?",
        text: "Tu personaje, tu historia o tu proyecto. Exploremos cómo convertir esa idea en una pieza con volumen y carácter.",
        button: "Solicitar una escultura",
        href: "/servicios/esculturas-3d",
        image: "banner-sculptures-wizard",
        features: [
            "Del boceto al volumen",
            "Detalles a tu medida",
            "Hecho contigo",
        ],
    },
    contact: {
        eyebrow: "Arte sin fronteras",
        title: "Creemos algo increíble",
        text: "Si tienes una idea, un personaje, un evento o simplemente quieres saludar, estaré encantado de leerte.",
        button: "Escríbeme ahora",
        href: "/contacto",
        image: "banner-contact-mandrill",
        features: ["Comparte tu idea", "Conversemos", "Démosle forma"],
    },
};
const banner = computed(() => banners[props.variant]);
</script>
<template>
    <section class="art-banner" :class="'art-banner-' + variant">
        <img
            class="art-banner-image"
            :src="'/images/compositions/' + banner.image + '.webp'"
            alt=""
            aria-hidden="true"
            width="1536"
            height="1024"
            loading="lazy"
        />
        <div class="container art-banner-inner">
            <div class="art-banner-copy">
                <p class="eyebrow">{{ banner.eyebrow }}</p>
                <h2>{{ banner.title }}</h2>
                <p>{{ banner.text }}</p>
                <button
                    v-if="variant === 'contact'"
                    class="button gold"
                    @click="emit('request')"
                >
                    {{ banner.button }}<PhArrowRight :size="19" />
                </button>
                <Link v-else :href="banner.href" class="button gold"
                    >{{ banner.button }}<PhArrowRight :size="19"
                /></Link>
                <ul class="banner-features">
                    <li>
                        <PhPenNib :size="24" weight="light" /><span>{{
                            banner.features[0]
                        }}</span>
                    </li>
                    <li>
                        <PhUsersThree :size="24" weight="light" /><span>{{
                            banner.features[1]
                        }}</span>
                    </li>
                    <li>
                        <PhHeart :size="24" weight="light" /><span>{{
                            banner.features[2]
                        }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>
