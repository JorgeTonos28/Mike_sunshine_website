<script setup lang="ts">
import { computed, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    PhArrowLeft,
    PhArrowRight,
    PhShoppingBag,
    PhTruck,
    PhChatCircleText,
    PhCheck,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BlockContent from "../components/BlockContent.vue";
import FormErrors from "../components/FormErrors.vue";
import { money, availability, type Artwork } from "../types";
const props = defineProps<{ product: Artwork }>();
const currentImage = ref(0);
const added = ref(false);
const selected = computed(() => props.product.images[currentImage.value]);
const form = useForm({ quantity: 1 });
const awaitingAvailability = computed(
    () => form.quantity > props.product.available_quantity,
);
const add = () =>
    form.post(`/carrito/${props.product.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            added.value = true;
        },
    });
</script>
<template>
    <Head :title="product.title"
        ><meta
            name="description"
            :content="
                product.summary ||
                `${product.title}, una obra de Mike Sunshine.`
            " /></Head
    ><StoreLayout>
        <div class="container product-section">
            <Link
                :href="
                    product.kind === 'painting' ? '/pinturas' : '/esculturas'
                "
                class="back-link"
                ><PhArrowLeft :size="17" />
                {{
                    product.kind === "painting" ? "Pinturas" : "Esculturas"
                }}</Link
            >
            <div class="product-grid">
                <div class="product-gallery">
                    <a
                        v-if="selected"
                        :href="selected.path"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Abrir imagen ampliada"
                        ><img
                            class="product-main-image"
                            :src="selected.path"
                            :alt="selected.alt"
                            width="800"
                            height="1000"
                    /></a>
                    <div v-else class="image-empty">Imagen pendiente</div>
                    <div
                        v-if="product.images.length > 1"
                        class="image-thumbnails"
                    >
                        <button
                            v-for="(image, i) in product.images"
                            :key="image.id"
                            :class="{ active: i === currentImage }"
                            :aria-label="`Ver imagen ${i + 1}`"
                            :aria-pressed="i === currentImage"
                            @click="currentImage = i"
                        >
                            <img
                                :src="image.path"
                                :alt="image.alt"
                                loading="lazy"
                            />
                        </button>
                    </div>
                </div>
                <div class="product-overview">
                    <p class="eyebrow">
                        {{
                            product.category ||
                            (product.kind === "painting"
                                ? "Pintura"
                                : "Escultura 3D")
                        }}
                    </p>
                    <h1>{{ product.title }}</h1>
                    <img
                        class="detail-brush"
                        src="/images/design/brush-gold.png"
                        alt=""
                        width="210"
                        height="28"
                    />
                    <p class="product-summary">{{ product.summary }}</p>
                    <dl class="product-specs">
                        <template
                            v-for="(value, key) in {
                                Técnica: product.technique,
                                Dimensiones: product.dimensions,
                                Edición: product.edition,
                                Año: product.year,
                            }"
                            :key="key"
                            ><div v-if="value">
                                <dt>{{ key }}</dt>
                                <dd>{{ value }}</dd>
                            </div></template
                        >
                    </dl>
                    <p class="gold-text availability-label">
                        {{ availability[product.availability] }}
                    </p>
                    <p class="product-price">
                        {{ money(product.price_minor, product.currency) }}
                    </p>
                    <p class="muted small-text">
                        El estudio confirmará los detalles de la pieza y su
                        entrega antes de la compra.
                    </p>
                    <p
                        v-if="awaitingAvailability"
                        class="availability-notice"
                        role="status"
                    >
                        {{
                            product.available_quantity
                                ? `Hay ${product.available_quantity} ${product.available_quantity === 1 ? "pieza disponible" : "piezas disponibles"} ahora.`
                                : "Esta obra no tiene existencias disponibles ahora."
                        }}
                        Puedes enviar tu solicitud. Mike te contactará cuando
                        esté disponible la cantidad que necesitas.
                    </p>
                    <FormErrors :errors="form.errors" />
                    <form
                        v-if="product.requestable"
                        class="product-add-form"
                        @submit.prevent="add"
                    >
                        <label class="product-quantity" for="product-quantity"
                            >Cantidad
                            <select
                                id="product-quantity"
                                v-model.number="form.quantity"
                            >
                                <option
                                    v-for="quantity in 10"
                                    :key="quantity"
                                    :value="quantity"
                                >
                                    {{ quantity }}
                                </option>
                            </select>
                        </label>
                        <button
                            class="button gold"
                            type="submit"
                            :disabled="form.processing"
                        >
                            <PhCheck v-if="added" :size="21" /><PhShoppingBag
                                v-else
                                :size="21"
                            />{{
                                form.processing
                                    ? "Añadiendo..."
                                    : added
                                      ? "Añadida a tu selección"
                                      : "Añadir al carrito"
                            }}</button
                        ><Link v-if="added" href="/carrito" class="text-link"
                            >Ver selección <PhArrowRight :size="18"
                        /></Link>
                    </form>
                    <Link v-else href="/contacto" class="button outline"
                        >Consultar al estudio <PhArrowRight :size="18"
                    /></Link>
                    <div class="purchase-notes">
                        <p>
                            <PhChatCircleText :size="21" weight="light" />
                            Atención directa con el artista
                        </p>
                        <p>
                            <PhTruck :size="21" weight="light" /> Entrega y
                            envío por acordar
                        </p>
                    </div>
                </div>
            </div>
            <section class="product-story">
                <BlockContent :blocks="product.content_blocks" />
            </section>
        </div>
    </StoreLayout>
</template>
