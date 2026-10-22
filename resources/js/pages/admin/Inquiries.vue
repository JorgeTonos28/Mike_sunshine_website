<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "../../layouts/AdminLayout.vue";
import Pagination from "../../components/Pagination.vue";
import type { Pagination as PageData } from "../../types";
type Inquiry = {
    id: number;
    name: string;
    email: string;
    subject: string;
    message: string;
    read_at: string | null;
    created_at: string;
    notification_status: string;
};
defineProps<{ inquiries: PageData<Inquiry> }>();
const subjects: Record<string, string> = {
    painting: "Pintura",
    sculpture: "Escultura",
    commission: "Encargo",
    collaboration: "Colaboración",
    other: "Consulta",
};
</script>
<template>
    <Head title="Mensajes" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>Buenas conversaciones.</h1>
                <p>Consultas, encargos e ideas que llegan al estudio.</p>
            </div>
        </div>
        <div v-if="inquiries.data.length" class="inquiry-list">
            <article
                v-for="inquiry in inquiries.data"
                :key="inquiry.id"
                class="admin-panel"
            >
                <div class="panel-heading">
                    <div>
                        <span
                            class="status-tag"
                            :class="{ published: !inquiry.read_at }"
                            >{{ inquiry.read_at ? "Leído" : "Nuevo" }}</span
                        >
                        <h2>{{ inquiry.name }}</h2>
                    </div>
                    <span class="muted small-text">{{
                        new Date(inquiry.created_at).toLocaleDateString("es-DO")
                    }}</span>
                </div>
                <p>
                    <a :href="`mailto:${inquiry.email}`" class="gold-text">{{
                        inquiry.email
                    }}</a
                    ><span class="muted">
                        / {{ subjects[inquiry.subject] }}</span
                    >
                </p>
                <p class="preserve-lines">{{ inquiry.message }}</p>
                <div class="inline-actions">
                    <a :href="`mailto:${inquiry.email}`" class="text-link"
                        >Responder por correo</a
                    ><button
                        v-if="!inquiry.read_at"
                        class="text-link"
                        @click="
                            router.put(
                                `/admin/inquiries/${inquiry.id}/read`,
                                {},
                                { preserveScroll: true },
                            )
                        "
                    >
                        Marcar como leído</button
                    ><span
                        v-if="inquiry.notification_status === 'failed'"
                        class="muted small-text"
                        >El aviso por correo falló. El mensaje se conserva
                        aquí.</span
                    >
                </div>
            </article>
        </div>
        <div v-else class="empty-state admin-panel">
            <h2>La siguiente idea está en camino.</h2>
            <p>Los mensajes del formulario de contacto se guardan aquí.</p>
        </div>
        <Pagination :links="inquiries.links"
    /></AdminLayout>
</template>
