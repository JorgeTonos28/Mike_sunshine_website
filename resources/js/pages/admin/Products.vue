<script setup lang="ts">
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { PhPlus, PhArrowUpRight } from "@phosphor-icons/vue";
import AdminLayout from "../../layouts/AdminLayout.vue";
import Pagination from "../../components/Pagination.vue";
import {
    money,
    availability,
    type Artwork,
    type Pagination as PageData,
} from "../../types";
const props = defineProps<{ products: PageData<Artwork>; search: string }>();
const searchValue = ref(props.search);
</script>
<template>
    <Head title="Obras del estudio" /><AdminLayout
        ><div class="admin-heading">
            <div>
                <h1>Las obras.</h1>
                <p>{{ products.total }} piezas en tu universo creativo.</p>
            </div>
            <Link href="/admin/products/create" class="button gold"
                ><PhPlus :size="18" /> Nueva obra</Link
            >
        </div>
        <form
            class="admin-search"
            @submit.prevent="
                router.get(
                    '/admin/products',
                    { search: searchValue },
                    { preserveState: true },
                )
            "
        >
            <label class="sr-only" for="admin-search">Buscar por título</label
            ><input
                id="admin-search"
                v-model="searchValue"
                placeholder="Buscar por título..."
                maxlength="100"
            /><button class="button outline" type="submit">Buscar</button>
        </form>
        <div class="admin-panel table-wrap" v-if="products.data.length">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Obra</th>
                        <th>Tipo</th>
                        <th>Disponibilidad</th>
                        <th>Precio</th>
                        <th>Publicación</th>
                        <th><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.id">
                        <td>
                            <Link
                                :href="`/admin/products/${product.id}/edit`"
                                class="admin-product-name"
                                ><img
                                    v-if="product.images[0]"
                                    :src="product.images[0].path"
                                    alt=""
                                    width="48"
                                    height="60"
                                /><strong>{{ product.title }}</strong></Link
                            >
                        </td>
                        <td>
                            {{
                                product.kind === "painting"
                                    ? "Pintura"
                                    : "Escultura"
                            }}
                        </td>
                        <td>{{ availability[product.availability] }}</td>
                        <td>
                            {{ money(product.price_minor, product.currency) }}
                        </td>
                        <td>
                            <span
                                class="status-tag"
                                :class="{ published: product.is_published }"
                                >{{
                                    product.is_published
                                        ? "Publicada"
                                        : "Borrador"
                                }}</span
                            >
                        </td>
                        <td>
                            <Link
                                :href="`/admin/products/${product.id}/edit`"
                                class="text-link"
                                >Editar <PhArrowUpRight :size="16"
                            /></Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="empty-state admin-panel">
            <h2>
                {{
                    search
                        ? "No encontramos esa obra."
                        : "El catálogo empieza contigo."
                }}
            </h2>
            <p>Crea una obra y publícala cuando esté lista.</p>
            <Link href="/admin/products/create" class="button gold"
                >Crear primera obra</Link
            >
        </div>
        <Pagination :links="products.links"
    /></AdminLayout>
</template>
