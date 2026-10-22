<script setup lang="ts">
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { PhArrowRight, PhArrowLeft } from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import FormErrors from "../components/FormErrors.vue";
import { money, type CartData, type Shared } from "../types";
const props = defineProps<{ cart: CartData; token: string }>();
const page = usePage<Shared>();
const form = useForm({
    token: props.token,
    customer_name: "",
    customer_email: "",
    customer_phone: "",
    delivery_method: "shipping",
    shipping_address: "",
    message: "",
    privacy: false,
    terms: false,
    website: "",
});
const submit = () => form.post("/solicitar", { preserveScroll: true });
</script>
<template>
    <Head title="Solicitar obras" /><StoreLayout
        ><section class="container section-space">
            <Link href="/carrito" class="back-link"
                ><PhArrowLeft :size="17" /> Tu selección</Link
            >
            <h1 class="page-title">Un paso más cerca.</h1>
            <p class="page-intro">
                Déjanos tus datos para confirmar los detalles de tu solicitud de
                forma personal.
            </p>
            <p
                v-if="cart.items.some((item) => item.awaiting_availability)"
                class="availability-notice checkout-availability"
            >
                Tu selección incluye obras pendientes de disponibilidad. Podemos
                registrar la solicitud y Mike te contactará cuando estén
                disponibles. El envío de este formulario no reserva piezas ni
                realiza un cobro.
            </p>
            <div class="checkout-grid">
                <form class="studio-form" @submit.prevent="submit">
                    <FormErrors :errors="form.errors" />
                    <div class="form-grid">
                        <label
                            >Nombre completo <span aria-hidden="true">*</span
                            ><input
                                v-model="form.customer_name"
                                required
                                autocomplete="name"
                                maxlength="160" /></label
                        ><label
                            >Correo electrónico <span aria-hidden="true">*</span
                            ><input
                                v-model="form.customer_email"
                                required
                                type="email"
                                autocomplete="email"
                                maxlength="255"
                        /></label>
                    </div>
                    <label
                        >Teléfono <span class="muted">(opcional)</span
                        ><input
                            v-model="form.customer_phone"
                            type="tel"
                            autocomplete="tel"
                            maxlength="50"
                    /></label>
                    <fieldset>
                        <legend>¿Cómo te gustaría recibir tu obra?</legend>
                        <div class="delivery-options">
                            <label class="check-label"
                                ><input
                                    v-model="form.delivery_method"
                                    type="radio"
                                    value="shipping"
                                />
                                Solicitar envío</label
                            ><label class="check-label"
                                ><input
                                    v-model="form.delivery_method"
                                    type="radio"
                                    value="collection"
                                />
                                Coordinar recogida</label
                            >
                        </div>
                    </fieldset>
                    <label v-if="form.delivery_method === 'shipping'"
                        >Dirección de envío <span aria-hidden="true">*</span
                        ><textarea
                            v-model="form.shipping_address"
                            required
                            autocomplete="street-address"
                            maxlength="1500"
                            rows="3"
                            placeholder="País, ciudad, código postal y dirección completa"
                        /><small
                            >La utilizaremos para cotizar y coordinar la
                            entrega.</small
                        ></label
                    ><label
                        >¿Algo que debamos saber?
                        <span class="muted">(opcional)</span
                        ><textarea
                            v-model="form.message"
                            rows="4"
                            maxlength="3000"
                        />
                    </label>
                    <div class="honey" aria-hidden="true">
                        <label
                            >Website<input
                                v-model="form.website"
                                tabindex="-1"
                                autocomplete="off"
                        /></label>
                    </div>
                    <label class="check-label"
                        ><input
                            v-model="form.privacy"
                            type="checkbox"
                            required
                        /><span
                            >He leído la
                            <a href="/privacidad" target="_blank" rel="noopener"
                                >política de privacidad</a
                            >
                            y autorizo el uso de mis datos para atender esta
                            solicitud.</span
                        ></label
                    ><label class="check-label"
                        ><input
                            v-model="form.terms"
                            type="checkbox"
                            required
                        /><span
                            >Acepto los
                            <a href="/terminos" target="_blank" rel="noopener"
                                >términos</a
                            >
                            y entiendo que esta solicitud está pendiente de
                            confirmación.</span
                        ></label
                    ><button
                        type="submit"
                        class="button gold"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? "Registrando solicitud..."
                                : "Enviar solicitud"
                        }}
                        <PhArrowRight :size="19" />
                    </button>
                </form>
                <aside class="order-summary">
                    <h2>Obras que te esperan.</h2>
                    <div
                        v-for="item in cart.items"
                        :key="item.product_id"
                        class="checkout-item"
                    >
                        <img
                            v-if="item.product?.images[0]"
                            :src="item.product.images[0].path"
                            :alt="item.product.images[0].alt"
                            width="60"
                            height="75"
                        />
                        <div>
                            <strong>{{
                                item.product?.title || "Obra no disponible"
                            }}</strong>
                            <p>
                                {{ item.quantity }}
                                {{ item.quantity === 1 ? "pieza" : "piezas" }}
                            </p>
                            <small
                                v-if="item.awaiting_availability"
                                class="gold-text"
                                >En espera de disponibilidad</small
                            >
                        </div>
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
                    <p>
                        El envío y cualquier detalle adicional se acuerdan
                        directamente con el estudio. Tu selección no queda
                        reservada automáticamente.
                    </p>
                </aside>
            </div>
        </section></StoreLayout
    >
</template>
