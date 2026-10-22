<script setup lang="ts">
import { computed, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhSquaresFour,
    PhPaintBrush,
    PhShoppingBag,
    PhUsers,
    PhGearSix,
    PhFileText,
    PhEnvelopeSimple,
    PhArrowUpRight,
    PhSignOut,
    PhList,
    PhX,
} from "@phosphor-icons/vue";
import type { Shared } from "../types";
const page = usePage<Shared>();
const isAdmin = computed(() => page.props.auth.user?.role === "admin");
const open = ref(false);
const links = computed(() => [
    { href: "/admin", label: "Resumen", icon: PhSquaresFour },
    { href: "/admin/products", label: "Obras", icon: PhPaintBrush },
    ...(isAdmin.value
        ? [
              {
                  href: "/admin/orders",
                  label: "Solicitudes",
                  icon: PhShoppingBag,
              },
              {
                  href: "/admin/inquiries",
                  label: "Mensajes",
                  icon: PhEnvelopeSimple,
              },
              { href: "/admin/settings", label: "El negocio", icon: PhGearSix },
              { href: "/admin/users", label: "Usuarios", icon: PhUsers },
              {
                  href: "/admin/legal",
                  label: "Páginas legales",
                  icon: PhFileText,
              },
          ]
        : []),
]);
const active = (href: string) =>
    href === "/admin" ? page.url === href : page.url.startsWith(href);
</script>
<template>
    <Head
        ><link
            v-if="page.props.studio.favicon_path"
            rel="icon"
            :href="page.props.studio.favicon_path" /><meta
            name="robots"
            content="noindex,nofollow"
    /></Head>
    <div class="admin-shell">
        <header class="admin-mobile-bar">
            <Link href="/admin">El estudio</Link
            ><button
                class="icon-button"
                :aria-expanded="open"
                aria-controls="admin-navigation"
                @click="open = !open"
                :aria-label="open ? 'Cerrar navegación' : 'Abrir navegación'"
            >
                <PhX v-if="open" :size="24" /><PhList v-else :size="24" />
            </button>
        </header>
        <aside
            id="admin-navigation"
            class="admin-sidebar"
            :class="{ 'is-open': open }"
        >
            <Link href="/admin" class="admin-brand"
                >{{ page.props.studio.name }}<span>El estudio</span></Link
            >
            <nav aria-label="Administración">
                <Link
                    v-for="item in links"
                    :key="item.href"
                    :href="item.href"
                    :class="{ active: active(item.href) }"
                    :aria-current="active(item.href) ? 'page' : undefined"
                    @click="open = false"
                    ><component :is="item.icon" :size="20" weight="light" />{{
                        item.label
                    }}</Link
                >
            </nav>
            <div class="admin-sidebar-bottom">
                <Link href="/">Ver la web <PhArrowUpRight :size="18" /></Link>
                <p>
                    {{ page.props.auth.user?.name
                    }}<small>{{ isAdmin ? "Administrador" : "Editor" }}</small>
                </p>
                <Link href="/logout" method="post" as="button"
                    ><PhSignOut :size="18" /> Cerrar sesión</Link
                >
            </div>
        </aside>
        <main class="admin-main">
            <div
                v-if="page.props.flash.success"
                role="status"
                class="admin-flash"
            >
                {{ page.props.flash.success }}
            </div>
            <slot />
        </main>
    </div>
</template>
