<script setup lang="ts">
import { computed, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhEnvelopeSimple,
    PhInstagramLogo,
    PhUser,
    PhCalendarBlank,
    PhWhatsappLogo,
    PhMapPin,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BrushHeading from "../components/BrushHeading.vue";
import InquiryForm from "../components/InquiryForm.vue";
import InquiryDialog from "../components/InquiryDialog.vue";
import SocialLinks from "../components/SocialLinks.vue";
import CommissionBanner from "../components/CommissionBanner.vue";
import type { Shared, StudioService } from "../types";
defineProps<{ services: StudioService[] }>();
const page = usePage<Shared>();
const studio = computed(() => page.props.studio);
const inquiry = ref<InstanceType<typeof InquiryDialog>>();
</script>
<template>
    <Head title="Contacto"
        ><meta
            name="description"
            content="Pinturas, esculturas, encargos y colaboraciones. Cuéntale tu idea a Mike Sunshine."
    /></Head>
    <StoreLayout>
        <section class="contact-v2 painted-hero">
            <img
                class="painted-hero-art"
                src="/images/compositions/hero-contact-jester.webp"
                alt="El bufón, personaje de una pintura de Mike Sunshine entre pinceladas doradas"
                width="1536"
                height="1024"
                fetchpriority="high"
            />
            <div class="container">
                <div class="contact-v2-copy">
                    <p class="eyebrow">Arte con alma</p>
                    <BrushHeading title="Hablemos de tu idea" large />
                    <p class="contact-intro">
                        Me encantaría conocer tu proyecto. Estoy abierto a
                        encargos, colaboraciones, participación en eventos y
                        cualquier pregunta sobre mi trabajo. Cuéntame tu idea y
                        conversemos sobre cómo hacerla realidad.
                    </p>
                    <InquiryForm compact />
                </div>
            </div>
        </section>
        <section class="contact-info-strip">
            <div class="container contact-info-grid">
                <article>
                    <span class="contact-info-icon"
                        ><PhEnvelopeSimple :size="30" weight="light"
                    /></span>
                    <div>
                        <h2>Correo</h2>
                        <p>Escríbeme directamente a:</p>
                        <a
                            v-if="studio.contact_email"
                            :href="'mailto:' + studio.contact_email"
                            >{{ studio.contact_email }}</a
                        ><button
                            v-else
                            class="text-link"
                            @click="inquiry?.open()"
                        >
                            Enviar un mensaje</button
                        ><small v-if="studio.phone">{{ studio.phone }}</small>
                    </div>
                </article>
                <article>
                    <span class="contact-info-icon"
                        ><PhInstagramLogo :size="30" weight="light"
                    /></span>
                    <div>
                        <h2>Mis redes</h2>
                        <p>Sígueme y ve mi proceso:</p>
                        <SocialLinks /><small
                            >Pinturas, esculturas, bocetos y más en mi día a
                            día.</small
                        >
                    </div>
                </article>
                <article>
                    <span class="contact-info-icon"
                        ><PhUser :size="30" weight="light"
                    /></span>
                    <div>
                        <h2>Encargos</h2>
                        <p>
                            Pinturas, esculturas 3D y proyectos personalizados.
                        </p>
                        <Link
                            href="/servicios/encargos-personalizados"
                            class="text-link"
                            >Cuéntame tu idea<PhArrowRight :size="17"
                        /></Link>
                    </div>
                </article>
                <article>
                    <span class="contact-info-icon"
                        ><PhCalendarBlank :size="30" weight="light"
                    /></span>
                    <div>
                        <h2>Eventos</h2>
                        <p>
                            Colaboraciones, exposiciones y participación en
                            eventos.
                        </p>
                        <Link
                            href="/servicios/eventos-colaboraciones"
                            class="text-link"
                            >Hablemos<PhArrowRight :size="17"
                        /></Link>
                    </div>
                </article>
            </div>
        </section>
        <section
            class="container contact-studio-address"
            aria-label="El estudio y sus canales"
        >
            <div>
                <p class="eyebrow">Desde Santo Domingo Este</p>
                <h2>{{ studio.legal_name || studio.name }}</h2>
                <p v-if="studio.address">
                    <PhMapPin :size="21" weight="light" />{{ studio.address }}
                </p>
                <small
                    >Coordina cualquier visita o recogida directamente con el
                    estudio.</small
                >
            </div>
            <a
                v-if="studio.phone"
                class="button button-outline"
                :href="`https://wa.me/${studio.phone.replace(/\D/g, '')}`"
                target="_blank"
                rel="noopener noreferrer"
                ><PhWhatsappLogo :size="22" />Hablemos por WhatsApp</a
            >
        </section>
        <section class="container section-space request-services">
            <div class="service-section-heading">
                <div>
                    <p class="eyebrow">¿En qué podemos trabajar?</p>
                    <h2 class="section-title">¿Qué puedes solicitar?</h2>
                </div>
                <p>
                    Cada proyecto es una nueva historia. Ya sea una pintura, una
                    escultura o una idea completamente original, me encanta
                    colaborar en proyectos que den vida a personajes y mundos
                    con alma.
                </p>
            </div>
            <div class="service-cards">
                <article v-for="service in services" :key="service.slug">
                    <Link
                        :href="'/servicios/' + service.slug"
                        class="service-card-image"
                        :aria-label="service.title"
                        ><img
                            :src="service.image"
                            :alt="service.title + ', arte de Mike Sunshine'"
                            width="800"
                            height="1000"
                            loading="lazy"
                            :class="{
                                'service-sculpture-image':
                                    service.subject === 'sculpture',
                            }"
                    /></Link>
                    <h3>
                        <Link :href="'/servicios/' + service.slug">{{
                            service.title
                        }}</Link>
                    </h3>
                    <p>{{ service.intro }}</p>
                    <Link :href="'/servicios/' + service.slug" class="text-link"
                        >{{ service.cta }}<PhArrowRight :size="18"
                    /></Link>
                </article>
            </div>
        </section>
        <section
            v-if="studio.address || studio.phone"
            class="container contact-location"
        >
            <PhUser :size="24" weight="light" />
            <div>
                <h2>{{ studio.name }}</h2>
                <p v-if="studio.address">{{ studio.address }}</p>
                <a
                    v-if="studio.phone"
                    :href="'tel:' + studio.phone.replace(/[^+0-9]/g, '')"
                    >{{ studio.phone }}</a
                ><small v-if="studio.contact_email?.endsWith('.example')"
                    >Información de muestra. Los datos definitivos se
                    actualizarán antes del lanzamiento.</small
                >
            </div>
        </section>
        <CommissionBanner variant="contact" @request="inquiry?.open()" />
        <InquiryDialog ref="inquiry" />
    </StoreLayout>
</template>
