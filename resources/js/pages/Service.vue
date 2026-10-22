<script setup lang="ts">
import { ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    PhArrowLeft,
    PhArrowRight,
    PhChatCircleText,
    PhPaintBrush,
    PhHandshake,
} from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BrushHeading from "../components/BrushHeading.vue";
import InquiryDialog from "../components/InquiryDialog.vue";
import SocialLinks from "../components/SocialLinks.vue";
import type { StudioService } from "../types";
defineProps<{ service: StudioService }>();
const inquiry = ref<InstanceType<typeof InquiryDialog>>();
</script>
<template>
    <Head :title="service.title"
        ><meta name="description" :content="service.intro"
    /></Head>
    <StoreLayout>
        <section class="service-hero painted-hero">
            <img
                class="painted-hero-art"
                :src="service.hero"
                alt=""
                width="1536"
                height="1024"
                fetchpriority="high"
            />
            <div class="container">
                <div class="service-hero-copy">
                    <Link href="/contacto" class="back-link"
                        ><PhArrowLeft :size="17" /> Contacto y servicios</Link
                    >
                    <p class="eyebrow">{{ service.eyebrow }}</p>
                    <BrushHeading :title="service.title" large />
                    <p class="page-intro">{{ service.intro }}</p>
                    <button class="button gold" @click="inquiry?.open()">
                        {{ service.cta }}<PhArrowRight :size="19" />
                    </button>
                </div>
            </div>
        </section>
        <section class="container service-description section-space">
            <div>
                <p class="eyebrow">Una pieza, tu historia</p>
                <h2 class="section-title">Empecemos por lo que imaginas.</h2>
                <p>{{ service.description }}</p>
                <SocialLinks labels />
            </div>
            <div class="service-brief">
                <h3>Lo que podemos definir juntos</h3>
                <ul>
                    <li v-for="detail in service.details" :key="detail">
                        {{ detail }}
                    </li>
                </ul>
                <p class="muted small-text">
                    La propuesta, el presupuesto, la disponibilidad y la entrega
                    se acuerdan personalmente antes de confirmar el trabajo.
                </p>
            </div>
        </section>
        <section class="service-process">
            <div class="container">
                <p class="eyebrow">De la idea a la conversación</p>
                <h2 class="section-title">Así empieza tu proyecto.</h2>
                <div class="service-process-grid">
                    <article>
                        <PhChatCircleText :size="32" weight="light" />
                        <h3>Cuéntame tu idea</h3>
                        <p>
                            Comparte el concepto y las referencias que tienes en
                            mente. El formulario lleva un mensaje preparado para
                            este servicio.
                        </p>
                    </article>
                    <article>
                        <PhPaintBrush :size="32" weight="light" />
                        <h3>Definimos la propuesta</h3>
                        <p>
                            Conversamos sobre el formato, los detalles, el
                            presupuesto y las posibilidades de realizar la
                            pieza.
                        </p>
                    </article>
                    <article>
                        <PhHandshake :size="32" weight="light" />
                        <h3>Acordamos los detalles</h3>
                        <p>
                            Mike confirma contigo las condiciones del encargo y
                            su entrega antes de comenzar.
                        </p>
                    </article>
                </div>
                <button class="button gold" @click="inquiry?.open()">
                    {{ service.cta }}<PhArrowRight :size="19" />
                </button>
            </div>
        </section>
        <InquiryDialog ref="inquiry" :service="service" />
    </StoreLayout>
</template>
