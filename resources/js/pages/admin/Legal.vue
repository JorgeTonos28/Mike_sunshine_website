<script setup lang="ts">
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { PhDownloadSimple } from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import BlockEditor from "../../components/BlockEditor.vue";
import BlockContent from "../../components/BlockContent.vue";
import FormErrors from "../../components/FormErrors.vue";
import type { Block } from "../../types";
type LegalPage = {
    id: number;
    title: string;
    slug: string;
    content_blocks: Block[];
    is_approved: boolean;
    version: number;
};
const props = defineProps<{ pages: LegalPage[] }>();
const selected = ref(props.pages[0]?.id);
const preview = ref(false);
const first = props.pages[0];
const form = useForm({
    title: first?.title || "",
    content_blocks: JSON.parse(
        JSON.stringify(first?.content_blocks || []),
    ) as Block[],
    is_approved: first?.is_approved || false,
});
const change = () => {
    const page = props.pages.find((p) => p.id === Number(selected.value));
    if (!page) return;
    form.title = page.title;
    form.content_blocks = JSON.parse(JSON.stringify(page.content_blocks));
    form.is_approved = page.is_approved;
    form.clearErrors();
};
const save = () =>
    form.put(`/admin/legal/${selected.value}`, { preserveScroll: true });
</script>
<template>
    <Head title="Páginas legales" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>Reglas claras.</h1>
                <p>
                    Privacidad, condiciones y cookies, con información real del
                    negocio.
                </p>
            </div>
            <a href="/admin/legal-questionnaire" class="button outline"
                ><PhDownloadSimple :size="18" /> Cuestionario para Mike</a
            >
        </div>
        <div class="notice">
            Los textos iniciales describen el funcionamiento técnico actual y
            son provisionales. Completa el cuestionario, ajusta las políticas y
            márcalas como aprobadas antes de abrir la tienda al público.
        </div>
        <form class="studio-form" @submit.prevent="save">
            <section class="admin-panel">
                <div class="form-grid">
                    <label
                        >Página<select v-model="selected" @change="change">
                            <option
                                v-for="page in pages"
                                :key="page.id"
                                :value="page.id"
                            >
                                {{ page.title }}
                            </option>
                        </select></label
                    ><label
                        >Título<input
                            v-model="form.title"
                            required
                            maxlength="180"
                    /></label>
                </div>
                <FormErrors :errors="form.errors" />
                <div class="panel-heading">
                    <h2>Contenido</h2>
                    <button
                        type="button"
                        class="text-link"
                        @click="preview = !preview"
                    >
                        {{ preview ? "Editar contenido" : "Vista previa" }}
                    </button>
                </div>
                <BlockContent
                    v-if="preview"
                    :blocks="form.content_blocks"
                /><BlockEditor v-else v-model="form.content_blocks" /><label
                    class="check-label legal-approval"
                    ><input v-model="form.is_approved" type="checkbox" /><span
                        >He revisado y aprobado este texto para el negocio.
                        Quitar el aviso de borrador de la web.</span
                    ></label
                ><button
                    class="button gold"
                    type="submit"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Guardando..." : "Guardar página" }}
                </button>
            </section>
        </form></AdminLayout
    >
</template>
