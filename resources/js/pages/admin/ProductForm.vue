<script setup lang="ts">
import { computed, ref, watch, onBeforeUnmount } from "vue";
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
import {
    PhArrowLeft,
    PhFloppyDisk,
    PhArrowUpRight,
    PhTrash,
    PhArrowUp,
    PhArrowDown,
} from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import FormErrors from "../../components/FormErrors.vue";
import BlockEditor from "../../components/BlockEditor.vue";
import BlockContent from "../../components/BlockContent.vue";
import type { Artwork, Block, Shared } from "../../types";
const props = defineProps<{ product: Artwork | null }>();
const page = usePage<Shared>();
const form = useForm({
    title: props.product?.title || "",
    slug: props.product?.slug || "",
    kind: props.product?.kind || "painting",
    category: props.product?.category || "",
    summary: props.product?.summary || "",
    technique: props.product?.technique || "",
    dimensions: props.product?.dimensions || "",
    edition: props.product?.edition || "",
    year: props.product?.year ?? (null as number | null),
    price:
        props.product?.price_minor == null
            ? ""
            : (props.product.price_minor / 100).toFixed(2),
    stock: props.product?.stock ?? 1,
    availability: props.product?.availability || "inquiry",
    is_published: props.product?.is_published || false,
    is_featured: props.product?.is_featured || false,
    content_blocks: (props.product?.content_blocks || []) as Block[],
    uploads: [] as File[],
    remove_images: [] as number[],
    image_order: props.product?.images.map((image) => image.id) || [],
    image_alts:
        props.product?.images.map((image) => ({
            id: image.id,
            alt: image.alt,
        })) || [],
});
const orderedImages = computed(() =>
    form.image_order
        .map((id) => props.product?.images.find((image) => image.id === id))
        .filter((image) => image !== undefined),
);
const moveImage = (index: number, offset: number) => {
    const target = index + offset;
    if (target < 0 || target >= form.image_order.length) return;
    const id = form.image_order.splice(index, 1)[0];
    form.image_order.splice(target, 0, id);
};
const slugTouched = ref(false);
const preview = ref(false);
const archive = ref(false);
const filePreview = ref<string[]>([]);
const heading = computed(() =>
    props.product ? "Una obra, su historia." : "Dale forma a una nueva obra.",
);
watch(
    () => form.title,
    (title) => {
        if (!props.product && !slugTouched.value)
            form.slug = title
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-|-$/g, "");
    },
);
const files = (event: Event) => {
    filePreview.value.forEach(URL.revokeObjectURL);
    form.uploads = Array.from((event.target as HTMLInputElement).files || []);
    filePreview.value = form.uploads.map((f) => URL.createObjectURL(f));
};
onBeforeUnmount(() => filePreview.value.forEach(URL.revokeObjectURL));
const toggleRemoval = (id: number) => {
    const index = form.remove_images.indexOf(id);
    if (index >= 0) form.remove_images.splice(index, 1);
    else form.remove_images.push(id);
};
const save = () =>
    form
        .transform((data) => ({
            ...data,
            ...(props.product ? { _method: "put" } : {}),
        }))
        .post(
            props.product
                ? `/admin/products/${props.product.id}`
                : "/admin/products",
            {
                preserveScroll: true,
                onSuccess: () => {
                    form.uploads = [];
                    form.remove_images = [];
                    form.image_order =
                        props.product?.images.map((image) => image.id) || [];
                    form.image_alts =
                        props.product?.images.map((image) => ({
                            id: image.id,
                            alt: image.alt,
                        })) || [];
                    filePreview.value.forEach(URL.revokeObjectURL);
                    filePreview.value = [];
                },
            },
        );
</script>
<template>
    <Head
        :title="product ? `Editar ${product.title}` : 'Nueva obra'"
    /><AdminLayout
        ><Link href="/admin/products" class="back-link"
            ><PhArrowLeft :size="17" /> Todas las obras</Link
        >
        <div class="admin-heading">
            <div>
                <h1>{{ heading }}</h1>
                <p>Imágenes, detalles y una ficha con tu propia voz.</p>
            </div>
            <a
                v-if="product?.is_published"
                :href="`/obras/${product.slug}`"
                target="_blank"
                rel="noopener"
                class="button outline"
                >Ver en la web <PhArrowUpRight :size="17"
            /></a>
        </div>
        <form class="studio-form" @submit.prevent="save">
            <FormErrors :errors="form.errors" />
            <div class="admin-editor-grid">
                <div class="admin-editor-main">
                    <section class="admin-panel">
                        <h2>La obra</h2>
                        <label
                            >Título <span aria-hidden="true">*</span
                            ><input
                                v-model="form.title"
                                required
                                maxlength="180" /></label
                        ><label
                            >Dirección de la ficha
                            <span aria-hidden="true">*</span
                            ><input
                                v-model="form.slug"
                                required
                                maxlength="180"
                                pattern="[a-z0-9]+(-[a-z0-9]+)*"
                                @input="slugTouched = true"
                            /><small
                                >/obras/{{
                                    form.slug || "nombre-de-la-obra"
                                }}</small
                            ></label
                        >
                        <div class="form-grid">
                            <label
                                >Tipo<select v-model="form.kind">
                                    <option value="painting">Pintura</option>
                                    <option value="sculpture">
                                        Escultura 3D
                                    </option>
                                </select></label
                            ><label
                                >Categoría<input
                                    v-model="form.category"
                                    maxlength="80"
                                    placeholder="Por ejemplo, Fantasía"
                            /></label>
                        </div>
                        <label
                            >Presentación breve<textarea
                                v-model="form.summary"
                                maxlength="1500"
                                rows="3"
                            />
                        </label>
                    </section>
                    <section class="admin-panel">
                        <h2>Galería</h2>
                        <p class="muted small-text">
                            La primera imagen se usa como portada. PNG, JPG o
                            WebP; máximo 8 MB y 12 megapíxeles por archivo. Usa
                            imágenes reales o representaciones aprobadas por
                            Mike.
                        </p>
                        <div class="admin-image-grid">
                            <div
                                v-for="(image, index) in orderedImages"
                                :key="image.id"
                                :class="{
                                    removing: form.remove_images.includes(
                                        image.id,
                                    ),
                                }"
                            >
                                <img
                                    :src="image.path"
                                    :alt="image.alt"
                                    width="130"
                                    height="150"
                                />
                                <div class="image-move-controls">
                                    <button
                                        class="icon-button"
                                        type="button"
                                        :disabled="index === 0"
                                        :aria-label="`Adelantar imagen ${index + 1}`"
                                        @click="moveImage(index, -1)"
                                    >
                                        <PhArrowUp :size="15" /></button
                                    ><button
                                        class="icon-button"
                                        type="button"
                                        :disabled="
                                            index === orderedImages.length - 1
                                        "
                                        :aria-label="`Atrasar imagen ${index + 1}`"
                                        @click="moveImage(index, 1)"
                                    >
                                        <PhArrowDown :size="15" />
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="toggleRemoval(image.id)"
                                >
                                    {{
                                        form.remove_images.includes(image.id)
                                            ? "Conservar"
                                            : "Quitar"
                                    }}
                                </button>
                            </div>
                            <div v-for="(url, index) in filePreview" :key="url">
                                <img
                                    :src="url"
                                    :alt="`Nueva imagen ${index + 1}`"
                                    width="130"
                                    height="150"
                                /><span>Nueva imagen</span>
                            </div>
                        </div>
                        <div class="image-alt-fields">
                            <label
                                v-for="image in form.image_alts"
                                :key="image.id"
                                >Descripción accesible de imagen
                                {{ form.image_order.indexOf(image.id) + 1
                                }}<input v-model="image.alt" maxlength="255"
                            /></label>
                        </div>
                        <label
                            >Añadir imágenes<input
                                type="file"
                                multiple
                                accept="image/png,image/jpeg,image/webp"
                                @change="files"
                        /></label>
                        <p v-if="form.progress" role="status" class="gold-text">
                            Subiendo imágenes: {{ form.progress.percentage }} %
                        </p>
                    </section>
                    <section class="admin-panel">
                        <div class="panel-heading">
                            <h2>La historia y los detalles</h2>
                            <button
                                type="button"
                                class="text-link"
                                @click="preview = !preview"
                            >
                                {{
                                    preview ? "Volver a editar" : "Vista previa"
                                }}
                            </button>
                        </div>
                        <BlockContent
                            v-if="preview"
                            :blocks="form.content_blocks"
                        /><BlockEditor v-else v-model="form.content_blocks" />
                    </section>
                </div>
                <aside class="admin-editor-side">
                    <section class="admin-panel">
                        <h2>Publicación</h2>
                        <label class="check-label"
                            ><input
                                v-model="form.is_published"
                                type="checkbox"
                            /><span>Publicar en la web</span></label
                        ><label class="check-label"
                            ><input
                                v-model="form.is_featured"
                                type="checkbox"
                            /><span>Destacar en inicio</span></label
                        ><label
                            >Disponibilidad<select v-model="form.availability">
                                <option value="inquiry">
                                    Consultar disponibilidad
                                </option>
                                <option value="available">Disponible</option>
                                <option value="reserved">Reservada</option>
                                <option value="sold">Vendida</option>
                            </select></label
                        ><label
                            >Unidades<input
                                v-model.number="form.stock"
                                type="number"
                                min="0"
                                max="10000"
                                required /></label
                        ><label
                            >Precio ({{ page.props.studio.currency }})<input
                                v-model="form.price"
                                inputmode="decimal"
                                placeholder="Sin precio: a consultar"
                            /><small
                                >Utiliza punto para los decimales. Déjalo vacío
                                para cotizar.</small
                            ></label
                        ><button
                            class="button gold full-width"
                            :disabled="form.processing"
                            type="submit"
                        >
                            <PhFloppyDisk :size="18" />{{
                                form.processing
                                    ? "Guardando..."
                                    : "Guardar obra"
                            }}
                        </button>
                    </section>
                    <section class="admin-panel">
                        <h2>Ficha técnica</h2>
                        <label
                            >Técnica / material<input
                                v-model="form.technique"
                                maxlength="255"
                                placeholder="Dato confirmado por el artista" /></label
                        ><label
                            >Dimensiones<input
                                v-model="form.dimensions"
                                maxlength="255"
                                placeholder="Incluye unidad de medida" /></label
                        ><label
                            >Edición<input
                                v-model="form.edition"
                                maxlength="255" /></label
                        ><label
                            >Año<input
                                v-model.number="form.year"
                                type="number"
                                min="1900"
                                max="2100"
                        /></label>
                    </section>
                    <section
                        v-if="product && page.props.auth.user?.role === 'admin'"
                        class="admin-panel archive-panel"
                    >
                        <h2>Archivar obra</h2>
                        <p class="muted small-text">
                            Se ocultará del catálogo. Las solicitudes anteriores
                            conservarán sus datos.
                        </p>
                        <button
                            v-if="!archive"
                            type="button"
                            class="text-link"
                            @click="archive = true"
                        >
                            <PhTrash :size="17" /> Archivar
                        </button>
                        <div v-else>
                            <p>¿Archivar esta obra?</p>
                            <button
                                type="button"
                                class="button outline"
                                @click="
                                    router.delete(
                                        `/admin/products/${product.id}`,
                                    )
                                "
                            >
                                Sí, archivar</button
                            ><button
                                type="button"
                                class="icon-button"
                                @click="archive = false"
                            >
                                Cancelar
                            </button>
                        </div>
                    </section>
                </aside>
            </div>
        </form></AdminLayout
    >
</template>
