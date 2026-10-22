<script setup lang="ts">
import { useForm } from "@inertiajs/vue3";
import { PhArrowRight } from "@phosphor-icons/vue";
import FormErrors from "./FormErrors.vue";
const props = withDefaults(
    defineProps<{ subject?: string; message?: string; compact?: boolean }>(),
    { subject: "commission", message: "", compact: false },
);
const emit = defineEmits<{ submitted: [] }>();
const form = useForm({
    name: "",
    email: "",
    subject: props.subject,
    message: props.message,
    privacy: false,
    website: "",
});
const submit = () =>
    form.post("/contacto", {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit("submitted");
        },
    });
</script>
<template>
    <form
        class="studio-form inquiry-form"
        :class="{ 'inquiry-compact': compact }"
        @submit.prevent="submit"
    >
        <FormErrors :errors="form.errors" />
        <label
            ><span :class="{ 'sr-only': compact }">Nombre *</span
            ><input
                v-model="form.name"
                required
                autocomplete="name"
                maxlength="160"
                placeholder="Nombre *"
        /></label>
        <label
            ><span :class="{ 'sr-only': compact }">Correo electrónico *</span
            ><input
                v-model="form.email"
                required
                type="email"
                autocomplete="email"
                maxlength="255"
                placeholder="Correo electrónico *"
        /></label>
        <label v-if="!compact"
            >¿De qué te gustaría hablar?<select v-model="form.subject">
                <option value="painting">Una pintura</option>
                <option value="sculpture">Una escultura 3D</option>
                <option value="commission">Una comisión personalizada</option>
                <option value="collaboration">Un evento o colaboración</option>
                <option value="other">Otra idea</option>
            </select></label
        >
        <label
            ><span :class="{ 'sr-only': compact }">Mensaje *</span
            ><textarea
                v-model="form.message"
                required
                minlength="10"
                maxlength="5000"
                :rows="compact ? 3 : 5"
                placeholder="Mensaje *"
            />
        </label>
        <div class="honey" aria-hidden="true">
            <label
                >Website<input
                    v-model="form.website"
                    tabindex="-1"
                    autocomplete="off"
            /></label>
        </div>
        <label class="check-label"
            ><input v-model="form.privacy" type="checkbox" required /><span
                >He leído la
                <a href="/privacidad" target="_blank" rel="noopener"
                    >política de privacidad</a
                >
                y autorizo el uso de mis datos para responder a esta
                consulta.</span
            ></label
        >
        <button class="button gold" type="submit" :disabled="form.processing">
            {{ form.processing ? "Enviando..." : "Enviar mensaje"
            }}<PhArrowRight :size="19" />
        </button>
    </form>
</template>
