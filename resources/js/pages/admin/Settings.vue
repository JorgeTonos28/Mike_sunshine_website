<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { computed, onUnmounted, ref, watch } from "vue";
import { PhFloppyDisk, PhDownloadSimple } from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import FormErrors from "../../components/FormErrors.vue";
import type { Studio } from "../../types";
const props = defineProps<{ settings: Studio }>();
const form = useForm({
    name: props.settings.name,
    tagline: props.settings.tagline || "",
    bio: props.settings.bio || "",
    contact_email: props.settings.contact_email || "",
    phone: props.settings.phone || "",
    address: props.settings.address || "",
    instagram_url: props.settings.instagram_url || "",
    facebook_url: props.settings.facebook_url || "",
    youtube_url: props.settings.youtube_url || "",
    currency: props.settings.currency,
    hero_title: props.settings.hero_title,
    hero_description: props.settings.hero_description || "",
    logo: null as File | null,
    favicon: null as File | null,
    hero: null as File | null,
    portrait: null as File | null,
});
const portraitFile = ref<HTMLInputElement>();
const portraitPreview = ref<string>();
const portraitSource = computed(
    () =>
        portraitPreview.value ||
        props.settings.portrait_path ||
        "/images/artist/mike-portrait.webp",
);
watch(
    () => form.portrait,
    (file) => {
        if (portraitPreview.value) URL.revokeObjectURL(portraitPreview.value);
        portraitPreview.value =
            file &&
            ["image/png", "image/webp", "image/jpeg"].includes(file.type)
                ? URL.createObjectURL(file)
                : undefined;
    },
);
onUnmounted(() => {
    if (portraitPreview.value) URL.revokeObjectURL(portraitPreview.value);
});
const socials = [
    { key: "instagram_url", name: "Instagram" },
    { key: "facebook_url", name: "Facebook" },
    { key: "youtube_url", name: "YouTube" },
] as const;
const pick = (
    field: "logo" | "favicon" | "hero" | "portrait",
    event: Event,
) => {
    form[field] = (event.target as HTMLInputElement).files?.[0] || null;
};
const save = () =>
    form.post("/admin/settings", {
        preserveScroll: true,
        onSuccess: () => {
            form.logo = null;
            form.favicon = null;
            form.hero = null;
            form.portrait = null;
            if (portraitFile.value) portraitFile.value.value = "";
        },
    });
</script>
<template>
    <Head title="Información del negocio" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>La identidad del estudio.</h1>
                <p>La información que da vida a tu sitio.</p>
            </div>
        </div>
        <form class="studio-form" @submit.prevent="save">
            <FormErrors :errors="form.errors" />
            <div class="admin-settings-grid">
                <section class="admin-panel">
                    <h2>Tu negocio</h2>
                    <label
                        >Nombre del artista / negocio<input
                            v-model="form.name"
                            required
                            maxlength="180" /></label
                    ><label
                        >Disciplinas / presentación breve<input
                            v-model="form.tagline"
                            maxlength="255" /></label
                    ><label
                        >Sobre el artista<textarea
                            v-model="form.bio"
                            rows="6"
                            maxlength="8000"
                        /></label
                    ><label
                        >Moneda del catálogo<select v-model="form.currency">
                            <option value="DOP">Peso dominicano (DOP)</option>
                            <option value="USD">Dólar (USD)</option>
                            <option value="EUR">Euro (EUR)</option></select
                        ><small
                            >Los pedidos históricos conservan su moneda.
                            Cambiarla exige retirar primero los precios de obras
                            existentes.</small
                        ></label
                    >
                </section>
                <section class="admin-panel">
                    <h2>Contacto y redes</h2>
                    <label
                        >Correo del estudio<input
                            v-model="form.contact_email"
                            type="email"
                            maxlength="255"
                        /><small
                            >Recibirá avisos de solicitudes y se mostrará como
                            contacto en la web.</small
                        ></label
                    ><label
                        >Teléfono<input
                            v-model="form.phone"
                            type="tel"
                            maxlength="60" /></label
                    ><label
                        >Dirección pública<textarea
                            v-model="form.address"
                            rows="2"
                            maxlength="1000"
                        /><small
                            >Incluye únicamente la dirección que quieras
                            publicar.</small
                        ></label
                    ><label v-for="social in socials" :key="social.key"
                        >{{ social.name
                        }}<input
                            v-model="form[social.key]"
                            type="text"
                            inputmode="url"
                            placeholder="https://..."
                            maxlength="255"
                    /></label>
                </section>
                <section class="admin-panel">
                    <h2>Marca</h2>
                    <div class="branding-preview">
                        <img
                            v-if="settings.logo_path"
                            :src="settings.logo_path"
                            alt="Logo actual"
                        />
                    </div>
                    <label
                        >Nuevo logo<input
                            type="file"
                            accept="image/png,image/webp,image/jpeg"
                            @change="pick('logo', $event)"
                        /><small
                            >Preferiblemente PNG transparente con letras claras
                            para el fondo oscuro. Máximo 4 MB.</small
                        ></label
                    >
                    <div class="favicon-preview">
                        <img
                            v-if="settings.favicon_path"
                            :src="settings.favicon_path"
                            alt="Favicon actual"
                            width="40"
                            height="40"
                        />
                    </div>
                    <label
                        >Nuevo favicon<input
                            type="file"
                            accept="image/png,image/webp"
                            @change="pick('favicon', $event)"
                        /><small
                            >PNG o WebP, hasta 1024 × 1024 y 1 MB.</small
                        ></label
                    >
                </section>
                <section class="admin-panel">
                    <h2>Portada</h2>
                    <label
                        >Título de portada<input
                            v-model="form.hero_title"
                            required
                            maxlength="80" /></label
                    ><label
                        >Texto de portada<textarea
                            v-model="form.hero_description"
                            rows="3"
                            maxlength="400"
                        /></label
                    ><img
                        v-if="settings.hero_path"
                        :src="settings.hero_path"
                        alt="Imagen de portada actual"
                        class="hero-admin-preview"
                    /><label
                        >Nueva composición<input
                            type="file"
                            accept="image/png,image/webp,image/jpeg"
                            @change="pick('hero', $event)"
                        /><small
                            >PNG con transparencia para mantener el fondo.
                            Composición con espacio para texto a la izquierda,
                            máximo 8 MB y 12 megapíxeles.</small
                        ></label
                    >
                </section>
                <section class="admin-panel">
                    <h2>Retrato del artista</h2>
                    <p class="muted">
                        La foto que se muestra en la página Sobre mí.
                    </p>
                    <img
                        :src="portraitSource"
                        alt="Vista previa del retrato del artista"
                        class="portrait-admin-preview"
                    />
                    <label>
                        Nueva foto del artista
                        <input
                            ref="portraitFile"
                            type="file"
                            accept="image/png,image/webp,image/jpeg"
                            @change="pick('portrait', $event)"
                        />
                        <small
                            >PNG, WebP o JPG, hasta 8 MB y 12 megapíxeles. Un
                            fondo transparente se integra mejor con el diseño.
                            Revisa la vista previa y guarda la información para
                            publicarla.</small
                        >
                    </label>
                    <a href="/admin/about-questionnaire" class="text-link"
                        ><PhDownloadSimple :size="18" />Cuestionario para Sobre
                        mí</a
                    >
                </section>
            </div>
            <div class="admin-save-row">
                <button
                    class="button gold"
                    :disabled="form.processing"
                    type="submit"
                >
                    <PhFloppyDisk :size="18" />{{
                        form.processing ? "Guardando..." : "Guardar información"
                    }}</button
                ><span v-if="form.progress" class="muted"
                    >Subiendo: {{ form.progress.percentage }} %</span
                >
            </div>
        </form></AdminLayout
    >
</template>
