<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BlockContent from "../components/BlockContent.vue";
import type { Block, Shared } from "../types";
const studio = computed(() => usePage<Shared>().props.studio);
defineProps<{
    page: {
        title: string;
        content_blocks: Block[];
        is_approved: boolean;
        version: number;
        updated_at: string;
    };
}>();
</script>
<template>
    <Head :title="page.title"
        ><meta v-if="!page.is_approved" name="robots" content="noindex" /></Head
    ><StoreLayout
        ><article class="container legal-page">
            <p class="eyebrow">Información del estudio</p>
            <h1 class="page-title">{{ page.title }}</h1>
            <div v-if="!page.is_approved" class="notice">
                Texto provisional. Pendiente de completar los datos del negocio
                y de aprobación antes del lanzamiento.
            </div>
            <aside class="legal-identity" aria-label="Responsable y contacto">
                <div>
                    <span class="eyebrow">Responsable del estudio</span
                    ><strong>{{ studio.legal_name || studio.name }}</strong>
                    <p>{{ studio.address }}</p>
                </div>
                <div>
                    <a
                        v-if="studio.contact_email"
                        :href="`mailto:${studio.contact_email}`"
                        >{{ studio.contact_email }}</a
                    ><a
                        v-if="studio.phone"
                        :href="`tel:${studio.phone.replace(/[^+0-9]/g, '')}`"
                        >{{ studio.phone }}</a
                    ><span>Versión {{ page.version }}</span>
                </div>
            </aside>
            <BlockContent :blocks="page.content_blocks" />
            <p class="muted legal-updated">
                Última actualización:
                {{
                    new Date(page.updated_at).toLocaleDateString("es-DO", {
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                    })
                }}.
            </p>
            <nav class="legal-nav" aria-label="Información legal">
                <Link href="/privacidad">Privacidad</Link
                ><Link href="/terminos">Términos y condiciones</Link
                ><Link href="/cookies">Cookies</Link>
            </nav>
        </article></StoreLayout
    >
</template>
