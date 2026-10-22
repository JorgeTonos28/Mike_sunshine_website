<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
defineProps<{
    links: { url: string | null; label: string; active: boolean }[];
}>();
const label = (text: string) =>
    text.includes("Previous") ||
    text.includes("pagination.previous") ||
    text.includes("Anterior")
        ? "Anterior"
        : text.includes("Next") ||
            text.includes("pagination.next") ||
            text.includes("Siguiente")
          ? "Siguiente"
          : text.replace(/&[^;]+;/g, "").trim();
</script>
<template>
    <nav v-if="links.length > 3" class="pagination" aria-label="Paginación">
        <template v-for="(link, index) in links" :key="index"
            ><Link
                v-if="link.url"
                :href="link.url"
                :class="{ active: link.active }"
                :aria-current="link.active ? 'page' : undefined"
                preserve-scroll
                >{{ label(link.label) }}</Link
            ><span v-else>{{ label(link.label) }}</span></template
        >
    </nav>
</template>
