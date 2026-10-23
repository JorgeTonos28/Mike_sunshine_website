<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { PhCaretUp, PhCaretDown } from "@phosphor-icons/vue";

const visible = ref(false);
const dragging = ref(false);
const progress = ref(0);
const rail = ref<HTMLDivElement>();
const knob = ref<HTMLSpanElement>();
const knobHeight = 48;
let maxScroll = 0;
let travel = 0;
let knobY = 0;
let grabOffset: number | null = null;
let paintFrame = 0;
let observer: ResizeObserver | undefined;
let preference: MediaQueryList;
let contrast: MediaQueryList;
let stopNavigationListener: (() => void) | undefined;

function measure() {
    paintFrame = 0;
    const root = document.documentElement;
    maxScroll = Math.max(0, root.scrollHeight - root.clientHeight);
    visible.value = preference.matches && !contrast.matches && maxScroll > 8;
    root.toggleAttribute("data-mixer-scrollbar", visible.value);
    if (!visible.value) return;
    travel = Math.max(
        0,
        (rail.value?.clientHeight || window.innerHeight - 88) - knobHeight,
    );
    const fraction = maxScroll
        ? Math.max(0, Math.min(1, window.scrollY / maxScroll))
        : 0;
    knobY = fraction * travel;
    progress.value = Math.round(fraction * 100);
    if (knob.value)
        knob.value.style.transform = `translate3d(0, ${knobY}px, 0)`;
}

function scheduleMeasure() {
    if (!paintFrame) paintFrame = requestAnimationFrame(measure);
}

function moveTo(top: number) {
    window.scrollTo({
        top: Math.max(0, Math.min(maxScroll, top)),
        behavior: "instant",
    });
    scheduleMeasure();
}

function step(delta: number) {
    moveTo(window.scrollY + delta);
}

function pointToScroll(clientY: number) {
    if (!rail.value || !travel) return;
    const y =
        clientY -
        rail.value.getBoundingClientRect().top -
        (grabOffset ?? knobHeight / 2);
    moveTo((Math.max(0, Math.min(travel, y)) / travel) * maxScroll);
}

function pointerDown(event: PointerEvent) {
    if (event.button !== 0 || !rail.value) return;
    event.preventDefault();
    rail.value.focus({ preventScroll: true });
    grabOffset = (event.target as Element).closest(".mixer-knob")
        ? event.clientY - rail.value.getBoundingClientRect().top - knobY
        : knobHeight / 2;
    dragging.value = true;
    rail.value.setPointerCapture(event.pointerId);
    pointToScroll(event.clientY);
}

function pointerMove(event: PointerEvent) {
    if (dragging.value) pointToScroll(event.clientY);
}

function pointerEnd() {
    dragging.value = false;
    grabOffset = null;
}

function keyboard(event: KeyboardEvent) {
    const page = window.innerHeight * 0.85;
    const targets: Record<string, number> = {
        ArrowDown: window.scrollY + 60,
        ArrowUp: window.scrollY - 60,
        PageDown: window.scrollY + page,
        PageUp: window.scrollY - page,
        Home: 0,
        End: maxScroll,
        " ": window.scrollY + (event.shiftKey ? -page : page),
    };
    if (event.key in targets) {
        event.preventDefault();
        moveTo(targets[event.key]);
    }
}

onMounted(() => {
    preference = window.matchMedia(
        "(min-width: 900px) and (hover: hover) and (pointer: fine)",
    );
    contrast = window.matchMedia("(forced-colors: active)");
    preference.addEventListener("change", scheduleMeasure);
    contrast.addEventListener("change", scheduleMeasure);
    window.addEventListener("scroll", scheduleMeasure, { passive: true });
    window.addEventListener("resize", scheduleMeasure, { passive: true });
    observer = new ResizeObserver(scheduleMeasure);
    observer.observe(document.body);
    observer.observe(document.documentElement);
    stopNavigationListener = router.on("finish", scheduleMeasure);
    scheduleMeasure();
});

onBeforeUnmount(() => {
    cancelAnimationFrame(paintFrame);
    observer?.disconnect();
    preference?.removeEventListener("change", scheduleMeasure);
    contrast?.removeEventListener("change", scheduleMeasure);
    window.removeEventListener("scroll", scheduleMeasure);
    window.removeEventListener("resize", scheduleMeasure);
    stopNavigationListener?.();
    document.documentElement.removeAttribute("data-mixer-scrollbar");
});
</script>

<template>
    <aside
        v-show="visible"
        class="mixer-scrollbar"
        :class="{ 'is-dragging': dragging }"
        aria-label="Control de desplazamiento"
        data-native-cursor
    >
        <button
            type="button"
            class="mixer-step mixer-step-up"
            aria-label="Subir un tramo"
            @click="step(-80)"
        >
            <PhCaretUp :size="13" weight="bold" />
        </button>
        <div
            ref="rail"
            class="mixer-rail"
            role="scrollbar"
            tabindex="0"
            aria-label="Desplazamiento de la página"
            aria-controls="app"
            aria-orientation="vertical"
            :aria-valuenow="progress"
            :aria-valuetext="`${progress}% de la página`"
            :aria-valuemin="0"
            :aria-valuemax="100"
            @keydown="keyboard"
            @pointerdown="pointerDown"
            @pointermove="pointerMove"
            @pointerup="pointerEnd"
            @pointercancel="pointerEnd"
            @lostpointercapture="pointerEnd"
        >
            <span class="mixer-ruler" aria-hidden="true"></span>
            <span class="mixer-slot" aria-hidden="true"></span>
            <span ref="knob" class="mixer-knob" aria-hidden="true"
                ><span class="mixer-knob-ridge"></span
            ></span>
        </div>
        <button
            type="button"
            class="mixer-step mixer-step-down"
            aria-label="Bajar un tramo"
            @click="step(80)"
        >
            <PhCaretDown :size="13" weight="bold" />
        </button>
    </aside>
</template>
