<script setup lang="ts">
import { computed, ref } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import {
    PhShoppingBag,
    PhList,
    PhX,
    PhArrowUpRight,
    PhInstagramLogo,
    PhYoutubeLogo,
    PhFacebookLogo,
} from "@phosphor-icons/vue";
import type { Shared } from "../types";
const page = usePage<Shared>();
const studio = computed(() => page.props.studio);
const menu = ref(false);
const nav = [
    { href: "/", label: "Inicio" },
    { href: "/pinturas", label: "Pinturas" },
    { href: "/esculturas", label: "Esculturas" },
    { href: "/contacto", label: "Contacto" },
];
const active = (href: string) =>
    href === "/" ? page.url === "/" : page.url.startsWith(href);
</script>

<template>
    <Head>
        <link
            v-if="studio.favicon_path"
            rel="icon"
            type="image/png"
            :href="studio.favicon_path"
        />
        <meta name="theme-color" content="#11100e" />
    </Head>
    <a class="skip-link" href="#main-content">Saltar al contenido</a>
    <header class="site-header">
        <div class="container header-inner">
            <Link
                href="/"
                class="brand"
                :aria-label="`${studio.name}, inicio`"
                @click="menu = false"
            >
                <img
                    v-if="studio.logo_path"
                    :src="studio.logo_path"
                    :alt="studio.name"
                    width="250"
                    height="74"
                />
                <span v-else>{{ studio.name }}</span>
            </Link>
            <nav class="desktop-nav" aria-label="Navegación principal">
                <Link
                    v-for="item in nav"
                    :key="item.href"
                    :href="item.href"
                    :class="{ active: active(item.href) }"
                    :aria-current="active(item.href) ? 'page' : undefined"
                    >{{ item.label }}</Link
                >
            </nav>
            <div class="header-actions">
                <Link
                    href="/carrito"
                    class="cart-link"
                    :aria-label="`Carrito, ${page.props.cartCount} ${page.props.cartCount === 1 ? 'obra' : 'obras'}`"
                    ><PhShoppingBag :size="24" weight="light" /><span
                        v-if="page.props.cartCount"
                        class="cart-count"
                        >{{ page.props.cartCount }}</span
                    ></Link
                >
                <button
                    class="menu-toggle icon-button"
                    :aria-expanded="menu"
                    aria-controls="mobile-nav"
                    :aria-label="menu ? 'Cerrar menú' : 'Abrir menú'"
                    @click="menu = !menu"
                >
                    <PhX v-if="menu" :size="26" /><PhList v-else :size="26" />
                </button>
            </div>
        </div>
        <nav
            v-if="menu"
            id="mobile-nav"
            class="mobile-nav"
            aria-label="Navegación móvil"
        >
            <Link
                v-for="item in nav"
                :key="item.href"
                :href="item.href"
                :aria-current="active(item.href) ? 'page' : undefined"
                @click="menu = false"
                >{{ item.label }}</Link
            >
        </nav>
    </header>
    <div v-if="page.props.flash.success" class="flash container" role="status">
        {{ page.props.flash.success }}
    </div>
    <main id="main-content"><slot /></main>
    <footer class="site-footer">
        <div class="container footer-main">
            <div class="footer-identity">
                <Link href="/" class="footer-name">{{ studio.name }}</Link>
                <p>Pinturas, esculturas y un universo de personajes.</p>
            </div>
            <div class="footer-links">
                <Link href="/pinturas">Pinturas</Link
                ><Link href="/esculturas">Esculturas 3D</Link
                ><Link href="/contacto"
                    >Contacto <PhArrowUpRight :size="15"
                /></Link>
            </div>
            <div class="social-links">
                <a
                    v-if="studio.instagram_url"
                    :href="studio.instagram_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Instagram"
                    ><PhInstagramLogo :size="22"
                /></a>
                <a
                    v-if="studio.facebook_url"
                    :href="studio.facebook_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Facebook"
                    ><PhFacebookLogo :size="22"
                /></a>
                <a
                    v-if="studio.youtube_url"
                    :href="studio.youtube_url"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="YouTube"
                    ><PhYoutubeLogo :size="22"
                /></a>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© {{ new Date().getFullYear() }} {{ studio.name }}</span>
            <div>
                <Link href="/privacidad">Privacidad</Link
                ><Link href="/terminos">Términos</Link
                ><Link href="/cookies">Cookies</Link
                ><Link :href="page.props.auth.user ? '/admin' : '/login'"
                    >El estudio</Link
                >
            </div>
        </div>
    </footer>
</template>
