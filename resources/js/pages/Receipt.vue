<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { PhCheck, PhArrowRight } from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
defineProps<{ reference: string }>();
</script>
<template>
    <Head title="Solicitud recibida" /><StoreLayout
        ><section class="container receipt-section">
            <div class="receipt-check">
                <PhCheck :size="42" weight="light" />
            </div>
            <h1>Tu solicitud<br />ya está en el estudio.</h1>
            <p>
                Gracias por acercarte al universo de Mike Sunshine. El estudio
                revisará tu selección y se pondrá en contacto contigo.
            </p>
            <div class="receipt-reference">
                <span>Tu referencia</span><strong>{{ reference }}</strong>
            </div>
            <p class="muted">
                Todavía no se ha realizado ningún cobro ni reservado una obra.
            </p>
            <Link href="/pinturas" class="button gold"
                >Seguir explorando <PhArrowRight :size="19"
            /></Link></section
    ></StoreLayout>
</template>
