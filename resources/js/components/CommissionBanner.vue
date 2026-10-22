<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { PhArrowRight } from "@phosphor-icons/vue";
</script>
<template>
    <section class="commission-banner">
        <div class="container commission-inner">
            <div>
                <p class="eyebrow">Tu idea, su próximo universo</p>
                <h2>¿Una pieza<br />a tu medida?</h2>
                <p>
                    Un personaje, un retrato o algo que todavía no existe.
                    Cuéntame lo que imaginas.
                </p>
                <Link href="/contacto" class="button gold"
                    >Hablemos <PhArrowRight :size="19"
                /></Link>
            </div>
            <img
                src="/images/art/hero-wizard.png"
                alt=""
                loading="lazy"
                aria-hidden="true"
                width="1536"
                height="1024"
            />
        </div>
    </section>
</template>
