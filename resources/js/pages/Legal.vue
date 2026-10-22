<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import StoreLayout from "../layouts/StoreLayout.vue";
import BlockContent from "../components/BlockContent.vue";
import type { Block } from "../types";
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
