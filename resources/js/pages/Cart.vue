<script setup lang="ts">
import { computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhShoppingBag,
    PhTrash,
    PhArrowLeft,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import FormErrors from "../components/FormErrors.vue";
import { money, type CartData, type Shared } from "../types";
const props = defineProps<{ cart: CartData }>();
const page = usePage<Shared>();
const canContinue = computed(
    () =>
        props.cart.items.length > 0 &&
        props.cart.items.every(
            (i) => i.product?.requestable && i.quantity <= i.max_quantity,
        ),
);
const change = (id: number, event: Event) =>
    router.post(
        `/carrito/${id}`,
        { quantity: Number((event.target as HTMLSelectElement).value) },
        { preserveScroll: true },
    );
</script>
<template>
    <Head title="Tu selección" /><StoreLayout
        ><section class="container section-space cart-page">
            <p class="eyebrow">Un encuentro con el arte</p>
            <h1 class="page-title">Tu selección.</h1>
            <p class="page-intro">
                Reúne las obras que te interesan. El siguiente paso es conversar
                con el estudio.
            </p>
            <FormErrors :errors="page.props.errors" />
            <div v-if="cart.items.length" class="cart-grid">
                <div class="cart-items">
                    <article
                        v-for="item in cart.items"
                        :key="item.product_id"
                        class="cart-item"
                    >
                        <Link
                            v-if="item.product"
                            :href="`/obras/${item.product.slug}`"
                            class="cart-item-image"
                            ><img
                                v-if="item.product.images[0]"
                                :src="item.product.images[0].path"
                                :alt="item.product.images[0].alt"
                                width="100"
                                height="125"
                        /></Link>
                        <div class="cart-item-info">
                            <h2>
                                {{
                                    item.product?.title ||
                                    "Obra retirada del catálogo"
                                }}
                            </h2>
                            <p class="muted">
                                {{
                                    item.product
                                        ? money(
                                              item.product.price_minor,
                                              item.product.currency,
                                          )
                                        : "No disponible"
                                }}
                            </p>
                            <p
                                v-if="item.awaiting_availability"
                                class="availability-notice"
                            >
                                Solicitud en espera de disponibilidad. Mike te
                                contactará cuando pueda atender esta cantidad.
                            </p>
                            <p
                                v-if="
                                    !item.product?.requestable ||
                                    item.quantity > item.max_quantity
                                "
                                class="error-text"
                            >
                                Esta selección debe revisarse antes de
                                continuar.
                            </p>
                            <label
                                v-if="item.product?.requestable"
                                class="quantity-field"
                                >Cantidad<select
                                    :value="item.quantity"
                                    @change="change(item.product_id, $event)"
                                >
                                    <option
                                        v-for="quantity in item.max_quantity"
                                        :key="quantity"
                                        :value="quantity"
                                    >
                                        {{ quantity }}
                                    </option>
                                </select></label
                            >
                        </div>
                        <button
                            class="icon-button"
                            :aria-label="`Retirar ${item.product?.title || 'obra'}`"
                            @click="
                                router.delete(`/carrito/${item.product_id}`, {
                                    preserveScroll: true,
                                })
                            "
                        >
                            <PhTrash :size="21" />
                        </button>
                    </article>
                    <Link href="/pinturas" class="text-link"
                        ><PhArrowLeft :size="17" /> Seguir explorando</Link
                    >
                </div>
                <aside class="order-summary">
                    <h2>Tu próxima obra.</h2>
                    <div class="summary-row">
                        <span>Obras seleccionadas</span
                        ><span>{{
                            cart.items.reduce((n, i) => n + i.quantity, 0)
                        }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Importe de las obras</span
                        ><span>{{
                            cart.subtotal_minor === null
                                ? "Por confirmar"
                                : money(
                                      cart.subtotal_minor,
                                      page.props.studio.currency,
                                  )
                        }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Envío</span><span>Por acordar</span>
                    </div>
                    <p>
                        Enviarás una solicitud. Mike confirmará disponibilidad,
                        precio final y entrega contigo.
                    </p>
                    <Link
                        v-if="canContinue"
                        href="/solicitar"
                        class="button gold full-width"
                        >Solicitar estas obras <PhArrowRight :size="19"
                    /></Link>
                    <p v-else class="error-text">
                        Retira las obras que fueron retiradas del catálogo para
                        continuar.
                    </p>
                    <small>No se realiza ningún cobro en la web.</small>
                </aside>
            </div>
            <div v-else class="empty-state">
                <PhShoppingBag :size="55" weight="thin" />
                <h2>Elige algo que te hable.</h2>
                <p>Aún no has añadido obras a tu selección.</p>
                <Link href="/pinturas" class="button gold"
                    >Explorar pinturas <PhArrowRight :size="18"
                /></Link>
            </div></section
    ></StoreLayout>
</template>
