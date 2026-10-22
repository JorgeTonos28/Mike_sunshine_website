<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhCube,
    PhEnvelopeSimple,
    PhPaintBrush,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import ArtworkCard from "../components/ArtworkCard.vue";
import CommissionBanner from "../components/CommissionBanner.vue";
import type { Artwork, Shared } from "../types";
defineProps<{ products: Artwork[] }>();
const page = usePage<Shared>();
</script>
<template>
    <Head title="Inicio"
        ><meta
            name="description"
            content="Explora las pinturas y esculturas de Mike Sunshine. Personajes, retratos y mundos imaginarios. Consulta por una obra o una pieza personalizada." /><link
            rel="preload"
            as="image"
            :href="
                page.props.studio.hero_path || '/images/art/hero-wizard.webp'
            "
    /></Head>
    <StoreLayout>
        <section class="home-hero">
            <div class="hero-art-layer">
                <img
                    :src="
                        page.props.studio.hero_path ||
                        '/images/art/hero-wizard.webp'
                    "
                    alt="Mago de capucha violeta, representación de una pintura de Mike Sunshine"
                    fetchpriority="high"
                    width="1536"
                    height="1024"
                />
            </div>
            <div class="container hero-inner">
                <div class="hero-copy">
                    <h1>{{ page.props.studio.hero_title }}</h1>
                    <img
                        class="hero-brush"
                        src="/images/design/brush-gold.png"
                        alt=""
                        width="240"
                        height="30"
                    />
                    <p class="hero-disciplines">
                        {{ page.props.studio.tagline }}
                    </p>
                    <p class="hero-description">
                        {{ page.props.studio.hero_description }}
                    </p>
                    <div class="hero-buttons">
                        <Link href="/pinturas" class="button gold"
                            >Explorar arte <PhArrowRight :size="20" /></Link
                        ><Link href="/contacto" class="button outline"
                            >Hablemos</Link
                        >
                    </div>
                </div>
            </div>
        </section>
        <div class="category-strip">
            <div class="container category-strip-inner">
                <Link href="/pinturas" class="category-preview"
                    ><img
                        src="/images/art/cat.webp"
                        alt=""
                        width="140"
                        height="116" />
                    <div>
                        <h2>Pinturas</h2>
                        <p>Personajes, retratos<br />y mundos imaginarios.</p>
                        <PhArrowRight :size="24" /></div></Link
                ><Link href="/esculturas" class="category-preview"
                    ><div class="category-symbol">
                        <PhCube :size="59" weight="thin" />
                    </div>
                    <div>
                        <h2>Esculturas 3D</h2>
                        <p>Del boceto al volumen.<br />Otro modo de crear.</p>
                        <PhArrowRight :size="24" /></div></Link
                ><Link href="/contacto" class="category-preview contact-preview"
                    ><div class="category-symbol">
                        <PhEnvelopeSimple :size="45" weight="thin" />
                    </div>
                    <div>
                        <h2>Contacto</h2>
                        <p>
                            Encargos, colaboraciones<br />o simplemente saludar.
                        </p>
                        <PhArrowRight :size="24" /></div
                ></Link>
            </div>
        </div>
        <section class="container section-space" v-if="products.length">
            <div class="section-title-row">
                <div>
                    <p class="eyebrow">Dentro del universo</p>
                    <h2 class="section-title">
                        Personajes que cuentan historias.
                    </h2>
                </div>
                <Link href="/pinturas" class="text-link"
                    >Ver pinturas <PhArrowRight :size="19"
                /></Link>
            </div>
            <div class="artwork-grid featured-grid">
                <ArtworkCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </section>
        <section class="container artist-section">
            <div class="artist-art">
                <img
                    src="/images/art/mandrill.webp"
                    alt="Retrato de un mandril de Mike Sunshine"
                    loading="lazy"
                    width="800"
                    height="1000"
                />
            </div>
            <div class="artist-copy">
                <PhPaintBrush class="gold-text" :size="34" weight="light" />
                <h2>Más de una forma<br />de imaginar.</h2>
                <p>{{ page.props.studio.bio }}</p>
                <Link href="/contacto" class="text-link"
                    >Hablemos <PhArrowRight :size="19"
                /></Link>
            </div>
        </section>
        <CommissionBanner />
    </StoreLayout>
</template>
