<script setup lang="ts">
import { computed, reactive } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { PhArrowRight, PhMagnifyingGlass, PhCube } from "@phosphor-icons/vue";
import StoreLayout from "../layouts/StoreLayout.vue";
import BrushHeading from "../components/BrushHeading.vue";
import ArtworkCard from "../components/ArtworkCard.vue";
import Pagination from "../components/Pagination.vue";
import CommissionBanner from "../components/CommissionBanner.vue";
import type { Artwork, Pagination as PageData } from "../types";
const props = defineProps<{
    kind: string;
    products: PageData<Artwork>;
    categories: string[];
    filters: { search?: string; category?: string; sort?: string };
}>();
const painting = computed(() => props.kind === "painting");
const form = reactive({
    search: props.filters.search || "",
    category: props.filters.category || "",
    sort: props.filters.sort || "recent",
});
const apply = () =>
    router.get(
        painting.value ? "/pinturas" : "/esculturas",
        { ...form },
        { preserveScroll: true, preserveState: true },
    );
const setCategory = (category: string) => {
    form.category = category;
    apply();
};
</script>
<template>
    <Head :title="painting ? 'Pinturas' : 'Esculturas 3D'" /><StoreLayout>
        <section class="catalog-hero painted-hero catalog-hero-v2">
            <img
                class="painted-hero-art"
                :src="
                    painting
                        ? '/images/compositions/hero-paintings-monk.webp'
                        : '/images/compositions/hero-sculptures-cat.webp'
                "
                :alt="
                    painting
                        ? 'Sabio de túnica verde, pintura de Mike Sunshine'
                        : 'Gato esfinge, concepto escultórico basado en una pintura de Mike Sunshine'
                "
                width="1536"
                height="1024"
                fetchpriority="high"
            />
            <div class="container catalog-hero-inner">
                <div class="catalog-hero-copy">
                    <p class="eyebrow">
                        {{
                            painting
                                ? "Arte con alma"
                                : "Personajes en tres dimensiones"
                        }}
                    </p>
                    <BrushHeading
                        :title="painting ? 'Pinturas' : 'Esculturas 3D'"
                        large
                    />
                    <p>
                        {{
                            painting
                                ? "Personajes, retratos y mundos imaginarios. Cada obra abre una puerta a una historia diferente."
                                : "Del dibujo al volumen. Explora otra faceta del universo creativo de Mike Sunshine."
                        }}
                    </p>
                    <a href="#collection" class="text-link"
                        >{{ painting ? "Explorar obras" : "Ver colección" }}
                        <PhArrowRight :size="20"
                    /></a>
                </div>
            </div>
        </section>
        <section id="collection" class="container collection-section">
            <div class="catalog-toolbar">
                <div class="category-tabs" aria-label="Categorías">
                    <button
                        :class="{ active: !form.category }"
                        @click="setCategory('')"
                    >
                        Todas</button
                    ><button
                        v-for="category in categories"
                        :key="category"
                        :class="{ active: form.category === category }"
                        @click="setCategory(category)"
                    >
                        {{ category }}
                    </button>
                </div>
                <label class="sort-field"
                    ><span>Ordenar</span
                    ><select v-model="form.sort" @change="apply">
                        <option value="recent">Más recientes</option>
                        <option value="title">Nombre</option>
                        <option value="price-asc">Precio: menor a mayor</option>
                        <option value="price-desc">
                            Precio: mayor a menor
                        </option>
                    </select></label
                >
            </div>
            <form class="search-form" @submit.prevent="apply">
                <label class="sr-only" for="art-search">Buscar obras</label
                ><input
                    id="art-search"
                    v-model="form.search"
                    type="search"
                    placeholder="Buscar una obra..."
                    maxlength="100"
                /><button type="submit" class="icon-button" aria-label="Buscar">
                    <PhMagnifyingGlass :size="21" /></button
                ><span class="muted"
                    >{{ products.total }}
                    {{ products.total === 1 ? "obra" : "obras" }}</span
                >
            </form>
            <div
                v-if="products.data.length"
                class="artwork-grid catalog-artwork-grid"
            >
                <ArtworkCard
                    v-for="product in products.data"
                    :key="product.id"
                    :product="product"
                />
            </div>
            <div v-else class="empty-state">
                <PhCube v-if="!painting" :size="54" weight="thin" />
                <h2>
                    {{
                        form.search || form.category
                            ? "No encontramos esa obra."
                            : painting
                              ? "El próximo lienzo está por llegar."
                              : "Las próximas piezas están tomando forma."
                    }}
                </h2>
                <p>
                    {{
                        form.search || form.category
                            ? "Prueba otra búsqueda o explora todas las categorías."
                            : "El estudio publicará aquí las obras de esta colección. También puedes consultar por un encargo."
                    }}
                </p>
                <button
                    v-if="form.search || form.category"
                    class="button outline"
                    @click="
                        form.search = '';
                        form.category = '';
                        apply();
                    "
                >
                    Quitar filtros</button
                ><Link v-else href="/contacto" class="button gold"
                    >Hablemos <PhArrowRight :size="18"
                /></Link>
            </div>
            <Pagination :links="products.links" />
        </section>
        <section v-if="!painting" class="sculpture-process container">
            <div>
                <p class="eyebrow">Del concepto a la pieza final</p>
                <h2 class="section-title">
                    Una nueva dimensión para tus personajes.
                </h2>
            </div>
            <p>
                La idea se explora en un modelo, se definen sus posibilidades de
                fabricación y se conversa sobre su acabado. Las imágenes de esta
                colección son conceptos 3D derivados de pinturas de Mike; cada
                pieza se confirma personalmente con el estudio.
            </p>
            <Link href="/servicios/esculturas-3d" class="text-link"
                >Sobre el proceso<PhArrowRight :size="19"
            /></Link>
        </section>
        <CommissionBanner :variant="painting ? 'painting' : 'sculpture'" />
    </StoreLayout>
</template>
