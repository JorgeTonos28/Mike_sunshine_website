<script setup lang="ts">
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import FormErrors from "../../components/FormErrors.vue";
import type { Shared } from "../../types";
const props = defineProps<{ token: string | null; email: string }>();
const page = usePage<Shared>();
const form = useForm({
    email: props.email,
    token: props.token || "",
    password: "",
    password_confirmation: "",
});
const submit = () =>
    form.post(props.token ? "/reset-password" : "/forgot-password", {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
</script>
<template>
    <Head :title="token ? 'Establecer contraseña' : 'Recuperar acceso'"
        ><meta name="robots" content="noindex,nofollow"
    /></Head>
    <main class="auth-page password-page">
        <section class="auth-panel">
            <Link href="/" class="auth-brand"
                ><img
                    v-if="page.props.studio.logo_path"
                    :src="page.props.studio.logo_path"
                    :alt="page.props.studio.name"
                    width="240"
                    height="114"
            /></Link>
            <h1>{{ token ? "Un nuevo acceso." : "Vuelve a tu estudio." }}</h1>
            <p class="muted">
                {{
                    token
                        ? "Elige una contraseña de al menos 12 caracteres, con mayúsculas, minúsculas y números."
                        : "Si tu correo tiene acceso al estudio, recibirás un enlace para establecer una nueva contraseña."
                }}
            </p>
            <div v-if="page.props.flash.status" class="notice" role="status">
                {{ page.props.flash.status }}
            </div>
            <form class="studio-form" @submit.prevent="submit">
                <FormErrors :errors="form.errors" /><label
                    >Correo electrónico<input
                        v-model="form.email"
                        required
                        type="email"
                        autocomplete="email" /></label
                ><template v-if="token"
                    ><label
                        >Nueva contraseña<input
                            v-model="form.password"
                            required
                            type="password"
                            minlength="12"
                            maxlength="128"
                            autocomplete="new-password" /></label
                    ><label
                        >Confirmar contraseña<input
                            v-model="form.password_confirmation"
                            required
                            type="password"
                            minlength="12"
                            maxlength="128"
                            autocomplete="new-password" /></label></template
                ><button
                    class="button gold full-width"
                    :disabled="form.processing"
                >
                    {{
                        form.processing
                            ? "Procesando..."
                            : token
                              ? "Establecer contraseña"
                              : "Enviar enlace"
                    }}
                </button>
            </form>
            <Link href="/login" class="text-link auth-return"
                >Volver al acceso</Link
            >
        </section>
    </main>
</template>
