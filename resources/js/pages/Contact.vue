<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhEnvelopeSimple,
    PhInstagramLogo,
    PhPaintBrush,
    PhCube,
    PhPhone,
    PhMapPin,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BrushHeading from "../components/BrushHeading.vue";
import FormErrors from "../components/FormErrors.vue";
import type { Shared } from "../types";
const page = usePage<Shared>();
const studio = computed(() => page.props.studio);
const form = useForm({
    name: "",
    email: "",
    subject: "commission",
    message: "",
    privacy: false,
    website: "",
});
const submit = () =>
    form.post("/contacto", {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
</script>
<template>
    <Head title="Contacto" /><StoreLayout
        ><section class="contact-page container">
            <div class="contact-page-art" aria-hidden="true">
                <img
                    src="/images/art/hero-wizard.webp"
                    alt=""
                    width="1536"
                    height="1024"
                />
            </div>
            <div class="contact-page-copy">
                <p class="eyebrow">Arte que empieza conversando</p>
                <BrushHeading title="Hablemos de tu idea." large />
                <p class="page-intro">
                    Una pintura, una escultura, una colaboración. Cuéntame qué
                    tienes en mente y demos forma a la conversación.
                </p>
                <form class="studio-form" @submit.prevent="submit">
                    <FormErrors :errors="form.errors" /><label
                        >Tu nombre <span aria-hidden="true">*</span
                        ><input
                            v-model="form.name"
                            required
                            autocomplete="name"
                            maxlength="160" /></label
                    ><label
                        >Correo electrónico <span aria-hidden="true">*</span
                        ><input
                            v-model="form.email"
                            required
                            autocomplete="email"
                            type="email"
                            maxlength="255" /></label
                    ><label
                        >¿De qué te gustaría hablar?<select
                            v-model="form.subject"
                        >
                            <option value="commission">
                                Una pieza por encargo
                            </option>
                            <option value="painting">Una pintura</option>
                            <option value="sculpture">Una escultura</option>
                            <option value="collaboration">
                                Una colaboración
                            </option>
                            <option value="other">Otra idea</option>
                        </select></label
                    ><label
                        >Tu mensaje <span aria-hidden="true">*</span
                        ><textarea
                            v-model="form.message"
                            required
                            minlength="10"
                            maxlength="5000"
                            rows="4"
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
                            y autorizo el uso de mis datos para responder a esta
                            consulta.</span
                        ></label
                    ><button
                        class="button gold"
                        type="submit"
                        :disabled="form.processing"
                    >
                        {{ form.processing ? "Enviando..." : "Enviar mensaje" }}
                        <PhArrowRight :size="19" />
                    </button>
                </form>
            </div>
        </section>
        <section class="contact-options container">
            <div v-if="studio.phone">
                <PhPhone :size="30" weight="light" />
                <h2>Teléfono</h2>
                <a :href="`tel:${studio.phone.replace(/[^+0-9]/g, '')}`">{{
                    studio.phone
                }}</a>
            </div>
            <div v-if="studio.address">
                <PhMapPin :size="30" weight="light" />
                <h2>El estudio</h2>
                <p class="preserve-lines">{{ studio.address }}</p>
            </div>
            <div v-if="studio.contact_email">
                <PhEnvelopeSimple :size="30" weight="light" />
                <h2>Correo</h2>
                <a :href="`mailto:${studio.contact_email}`">{{
                    studio.contact_email
                }}</a>
            </div>
            <div v-if="studio.instagram_url">
                <PhInstagramLogo :size="30" weight="light" />
                <h2>Dentro del proceso</h2>
                <a
                    :href="studio.instagram_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    >Sígueme en Instagram <PhArrowRight :size="18"
                /></a>
            </div>
            <div>
                <PhPaintBrush :size="30" weight="light" />
                <h2>Pinturas</h2>
                <p>Retratos, personajes e ideas propias.</p>
            </div>
            <div>
                <PhCube :size="30" weight="light" />
                <h2>Esculturas 3D</h2>
                <p>Otra dimensión para tu idea.</p>
            </div>
        </section>
    </StoreLayout>
</template>
