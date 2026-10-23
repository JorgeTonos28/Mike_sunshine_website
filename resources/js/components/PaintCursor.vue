<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from "vue";
import { PhPaintBrush } from "@phosphor-icons/vue";
import { brushEnabled } from "../composables/brushPreference";

const cursor = ref<HTMLElement>();
const trail = ref<HTMLElement>();
const eligible = ref(false);
const drops = new Map<number, HTMLElement>();
let fine: MediaQueryList;
let reduced: MediaQueryList;
let frame = 0;
let x = 0;
let y = 0;
let previousX: number | null = null;
let previousY = 0;
let lastDrop = 0;

function hide() {
    document.documentElement.removeAttribute("data-paint-cursor");
    if (cursor.value) cursor.value.style.opacity = "0";
    previousX = null;
    cancelAnimationFrame(frame);
    frame = 0;
    for (const [timer, drop] of drops) {
        window.clearTimeout(timer);
        drop.remove();
    }
    drops.clear();
}
function updateEligibility() {
    eligible.value = fine.matches && !reduced.matches;
    if (!eligible.value) hide();
}
function move(event: PointerEvent) {
    if (
        !eligible.value ||
        !brushEnabled.value ||
        event.pointerType !== "mouse" ||
        !cursor.value ||
        !trail.value
    )
        return;
    const target = event.target instanceof Element ? event.target : null;
    if (
        target?.closest(
            "input, textarea, select, [contenteditable], dialog, [data-native-cursor]",
        )
    ) {
        hide();
        return;
    }
    x = event.clientX;
    y = event.clientY;
    document.documentElement.setAttribute("data-paint-cursor", "");
    if (!frame)
        frame = requestAnimationFrame(() => {
            if (cursor.value) {
                // After rotation the bristle tip is at (6, 4), the click hotspot.
                cursor.value.style.transform = `translate3d(${x - 6}px, ${y - 4}px, 0)`;
                cursor.value.style.opacity = "1";
            }
            frame = 0;
        });
    const distance =
        previousX === null ? 10 : Math.hypot(x - previousX, y - previousY);
    if (distance < 8 || event.timeStamp - lastDrop < 28) return;
    previousX = x;
    previousY = y;
    lastDrop = event.timeStamp;
    if (drops.size >= 24) {
        const oldest = drops.entries().next().value;
        if (oldest) {
            window.clearTimeout(oldest[0]);
            oldest[1].remove();
            drops.delete(oldest[0]);
        }
    }
    const dot = document.createElement("span");
    dot.className = "paint-drop";
    const size = 2 + Math.random() * 3;
    dot.style.width = `${size}px`;
    dot.style.height = `${size * (0.7 + Math.random() * 0.5)}px`;
    dot.style.left = `${x - size / 2}px`;
    dot.style.top = `${y - size / 2}px`;
    dot.style.backgroundColor = ["#d8a952", "#c0894d", "#b69c66"][
        Math.floor(Math.random() * 3)
    ];
    dot.style.setProperty("--paint-drift", `${Math.random() * 8 - 4}px`);
    trail.value.append(dot);
    const timer = window.setTimeout(() => {
        dot.remove();
        drops.delete(timer);
    }, 460);
    drops.set(timer, dot);
}
function keyboard(event: KeyboardEvent) {
    if (event.key === "Tab") hide();
}
function visibility() {
    if (document.hidden) hide();
}
watch(brushEnabled, (enabled) => {
    if (!enabled) hide();
});
onMounted(() => {
    fine = window.matchMedia("(hover: hover) and (pointer: fine)");
    reduced = window.matchMedia("(prefers-reduced-motion: reduce)");
    updateEligibility();
    fine.addEventListener("change", updateEligibility);
    reduced.addEventListener("change", updateEligibility);
    window.addEventListener("pointermove", move, { passive: true });
    window.addEventListener("blur", hide);
    document.addEventListener("pointerleave", hide);
    window.addEventListener("keydown", keyboard);
    document.addEventListener("visibilitychange", visibility);
});
onUnmounted(() => {
    hide();
    fine?.removeEventListener("change", updateEligibility);
    reduced?.removeEventListener("change", updateEligibility);
    window.removeEventListener("pointermove", move);
    window.removeEventListener("blur", hide);
    document.removeEventListener("pointerleave", hide);
    window.removeEventListener("keydown", keyboard);
    document.removeEventListener("visibilitychange", visibility);
});
</script>
<template>
    <div ref="trail" class="paint-trail" aria-hidden="true"></div>
    <div ref="cursor" class="paint-cursor" aria-hidden="true">
        <PhPaintBrush :size="32" weight="duotone" />
    </div>
    <button
        v-if="eligible"
        class="paint-toggle"
        type="button"
        aria-label="Efecto de pincel"
        :aria-pressed="brushEnabled"
        @click="brushEnabled = !brushEnabled"
    >
        <PhPaintBrush :size="14" />{{
            brushEnabled ? "Desactivar pincel" : "Activar pincel"
        }}
    </button>
</template>
