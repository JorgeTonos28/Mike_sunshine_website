<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { PhArrowUpRight } from "@phosphor-icons/vue";
import { money, availability, type Artwork } from "../types";
defineProps<{ product: Artwork }>();
</script>
<template>
    <article class="artwork-card">
        <Link :href="`/obras/${product.slug}`" class="artwork-card-link">
            <div class="artwork-picture">
                <img
                    v-if="product.images[0]"
                    :src="product.images[0].path"
                    :alt="product.images[0].alt"
                    loading="lazy"
                    width="800"
                    height="1000"
                />
                <div v-else class="image-empty">Imagen pendiente</div>
                <span class="artwork-arrow" aria-hidden="true"
                    ><PhArrowUpRight :size="23"
                /></span>
            </div>
            <div class="artwork-caption">
                <p class="artwork-category">
                    {{
                        product.category ||
                        (product.kind === "painting"
                            ? "Pintura"
                            : "Escultura 3D")
                    }}
                </p>
                <h3>{{ product.title }}</h3>
                <p v-if="product.technique || product.dimensions" class="muted">
                    {{
                        [product.technique, product.dimensions]
                            .filter(Boolean)
                            .join(" · ")
                    }}
                </p>
                <div class="artwork-caption-bottom">
                    <span :class="{ 'gold-text': product.requestable }">{{
                        availability[product.availability]
                    }}</span
                    ><span v-if="product.price_minor !== null">{{
                        money(product.price_minor, product.currency)
                    }}</span>
                </div>
            </div>
        </Link>
    </article>
</template>
