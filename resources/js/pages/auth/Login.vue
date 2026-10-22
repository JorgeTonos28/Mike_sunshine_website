<script setup lang="ts">
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { PhArrowRight, PhLockKey } from "@phosphor-icons/vue";
import FormErrors from "../../components/FormErrors.vue";
import type { Shared } from "../../types";
defineProps<{ googleEnabled: boolean }>();
const page = usePage<Shared>();
const form = useForm({ email: "", password: "", remember: false });
</script>
<template>
    <Head title="Entrar al estudio"
        ><meta name="robots" content="noindex,nofollow"
    /></Head>
    <main class="auth-page">
        <div class="auth-art">
            <img
                src="/images/art/hero-wizard.png"
                alt=""
                aria-hidden="true"
                width="1536"
                height="1024"
            />
        </div>
        <section class="auth-panel">
            <Link href="/" class="auth-brand"
                ><img
                    v-if="page.props.studio.logo_path"
                    :src="page.props.studio.logo_path"
                    :alt="page.props.studio.name"
                    width="240"
                    height="114"
            /></Link>
            <p class="admin-eyebrow">El estudio</p>
            <h1>Bienvenido a tu<br />espacio creativo.</h1>
            <p class="muted">
                Gestiona tus obras y las conversaciones que generan.
            </p>
            <div v-if="page.props.flash.status" class="notice" role="status">
                {{ page.props.flash.status }}
            </div>
            <form
                class="studio-form"
                @submit.prevent="
                    form.post('/login', {
                        onFinish: () => form.reset('password'),
                    })
                "
            >
                <FormErrors :errors="form.errors" /><label
                    >Correo electrónico<input
                        v-model="form.email"
                        required
                        type="email"
                        autocomplete="username"
                        maxlength="255" /></label
                ><label
                    >Contraseña<input
                        v-model="form.password"
                        required
                        type="password"
                        autocomplete="current-password"
                        maxlength="128"
                /></label>
                <div class="auth-options">
                    <label class="check-label"
                        ><input v-model="form.remember" type="checkbox" /><span
                            >Recordarme</span
                        ></label
                    ><Link href="/forgot-password"
                        >¿Olvidaste tu contraseña?</Link
                    >
                </div>
                <button
                    class="button gold full-width"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Entrando..." : "Entrar al estudio" }}
                    <PhArrowRight :size="19" />
                </button>
            </form>
            <template v-if="googleEnabled"
                ><div class="auth-divider"><span>o continúa con</span></div>
                <a
                    href="/auth/google"
                    class="button outline full-width google-button"
                    ><span class="google-g" aria-hidden="true">G</span>
                    Google</a
                ></template
            >
            <p class="auth-private">
                <PhLockKey :size="15" /> Acceso privado para usuarios
                autorizados.
            </p>
            <Link href="/" class="text-link auth-return"
                >Volver a la galería</Link
            >
        </section>
    </main>
</template>
