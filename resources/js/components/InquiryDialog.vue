<script setup lang="ts">
import { ref } from "vue";
import { PhX } from "@phosphor-icons/vue";
import InquiryForm from "./InquiryForm.vue";
import type { StudioService } from "../types";
defineProps<{ service?: StudioService }>();
const dialog = ref<HTMLDialogElement>();
const open = () => dialog.value?.showModal();
const close = () => dialog.value?.close();
defineExpose({ open });
</script>
<template>
    <dialog
        ref="dialog"
        class="inquiry-dialog"
        aria-labelledby="inquiry-dialog-title"
        @click="$event.target === dialog && close()"
    >
        <button
            class="icon-button dialog-close"
            type="button"
            aria-label="Cerrar formulario"
            @click="close"
        >
            <PhX :size="24" />
        </button>
        <p class="eyebrow">Una conversación con Mike</p>
        <h2 id="inquiry-dialog-title">
            {{ service ? service.cta : "Cuéntame tu idea." }}
        </h2>
        <p class="muted dialog-intro">
            {{
                service
                    ? "Hemos preparado el mensaje para tu proyecto. Puedes completarlo o enviarlo así junto a tus datos."
                    : "Una pintura, una escultura o una colaboración. Me encantará leerte."
            }}
        </p>
        <InquiryForm
            :subject="service?.subject"
            :message="service?.message"
            @submitted="close"
        />
    </dialog>
</template>
