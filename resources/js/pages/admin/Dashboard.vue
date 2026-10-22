<script setup lang="ts">
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhArrowRight,
    PhPlus,
    PhPaintBrush,
    PhShoppingBag,
    PhEnvelopeSimple,
} from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import type { Shared } from "../../types";
defineProps<{
    counts: {
        products: number;
        published: number;
        orders?: number | null;
        inquiries?: number | null;
    };
    setup?: { email: boolean; google: boolean };
}>();
const page = usePage<Shared>();
</script>
<template>
    <Head title="El estudio" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <p class="admin-eyebrow">Mike Sunshine / Administración</p>
                <h1>Un lugar para crear.</h1>
                <p>
                    Tu catálogo, las conversaciones y el día a día del estudio.
                </p>
            </div>
            <Link href="/admin/products/create" class="button gold"
                ><PhPlus :size="18" /> Nueva obra</Link
            >
        </div>
        <div class="admin-metrics">
            <div>
                <PhPaintBrush :size="25" weight="light" /><strong>{{
                    counts.products
                }}</strong
                ><span>Obras en el catálogo</span
                ><small>{{ counts.published }} publicadas</small>
            </div>
            <div v-if="counts.orders != null">
                <PhShoppingBag :size="25" weight="light" /><strong>{{
                    counts.orders
                }}</strong
                ><span>Solicitudes por revisar</span
                ><Link href="/admin/orders"
                    >Ver solicitudes <PhArrowRight :size="16"
                /></Link>
            </div>
            <div v-if="counts.inquiries != null">
                <PhEnvelopeSimple :size="25" weight="light" /><strong>{{
                    counts.inquiries
                }}</strong
                ><span>Mensajes sin leer</span
                ><Link href="/admin/inquiries"
                    >Abrir mensajes <PhArrowRight :size="16"
                /></Link>
            </div>
        </div>
        <div
            v-if="
                page.props.auth.user?.role === 'admin' && setup && !setup.email
            "
            class="notice"
        >
            Configura el correo del estudio en
            <Link href="/admin/settings" class="underlined">El negocio</Link> y
            el servicio SMTP para recibir avisos. Las solicitudes siempre quedan
            registradas en el panel.
        </div>
        <section class="admin-panel welcome-panel">
            <div>
                <h2>Tu próxima historia empieza aquí.</h2>
                <p>
                    Añade una pintura o una escultura, prepara sus imágenes y
                    cuenta lo que hay detrás de cada pieza. Puedes guardar un
                    borrador antes de publicarla.
                </p>
                <Link href="/admin/products" class="text-link"
                    >Gestionar obras <PhArrowRight :size="18"
                /></Link>
            </div>
            <img
                src="/images/art/wizard.webp"
                alt="Mago de capucha violeta"
                width="180"
                height="225"
            /></section
    ></AdminLayout>
</template>
