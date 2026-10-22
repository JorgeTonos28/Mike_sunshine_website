<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, useId } from "vue";
import { PhPause, PhPlay } from "@phosphor-icons/vue";

defineProps<{ src: string; alt: string }>();
const id = useId();
const portraitClip = `portrait-clip-${id}`;
const lowerRingClip = `portrait-ring-${id}`;
const upperRingClip = `portrait-ring-upper-${id}`;
const trailGradient = `portrait-trail-gradient-${id}`;
const trailBlur = `portrait-trail-blur-${id}`;
const frame = ref<HTMLDivElement>();
const light = ref<HTMLButtonElement>();
const trail = ref<SVGGElement>();
const frontTrail = ref<SVGGElement>();
const gradient = ref<SVGLinearGradientElement>();
const paused = ref(false);
const reducedMotion = ref(false);
const jumping = ref(false);
const burst = ref<{
    left: string;
    top: string;
    zIndex: string;
    key: number;
} | null>(null);
let phase = 4.05;
let animation = 0;
let previousTime = 0;
let inView = false;
let keyboardFocus = false;
let observer: IntersectionObserver | undefined;
let motionPreference: MediaQueryList | undefined;
let jumpTimer: ReturnType<typeof setTimeout> | undefined;
let settleTimer: ReturnType<typeof setTimeout> | undefined;
let jumpLocked = false;
const rotation = (-16 * Math.PI) / 180;
const turn = Math.PI * 2;

function point(angle: number) {
    const x = 480 * Math.cos(angle);
    const y = 445 * Math.sin(angle);
    return {
        x: 500 + x * Math.cos(rotation) - y * Math.sin(rotation),
        y: 485 + x * Math.sin(rotation) + y * Math.cos(rotation),
    };
}

function paint() {
    if (!light.value) return;
    const position = point(phase);
    light.value.style.left = `${position.x / 10}%`;
    light.value.style.top = `${position.y / 10}%`;
    light.value.style.zIndex = position.y >= 485 ? "3" : "0";
    const tail = point(phase - 0.24);
    const arc = `M ${tail.x} ${tail.y} A 480 445 -16 0 1 ${position.x} ${position.y}`;
    gradient.value?.setAttribute("x1", String(tail.x));
    gradient.value?.setAttribute("y1", String(tail.y));
    gradient.value?.setAttribute("x2", String(position.x));
    gradient.value?.setAttribute("y2", String(position.y));
    [trail.value, frontTrail.value].forEach((layer) => {
        layer
            ?.querySelectorAll("path")
            .forEach((path) => path.setAttribute("d", arc));
    });
}

function canAnimate() {
    return (
        inView &&
        !document.hidden &&
        !paused.value &&
        !reducedMotion.value &&
        !keyboardFocus &&
        !jumping.value
    );
}

function tick(time: number) {
    animation = 0;
    if (!canAnimate()) return;
    if (previousTime)
        phase =
            (phase + (Math.min(time - previousTime, 64) / 22000) * turn) % turn;
    previousTime = time;
    paint();
    animation = requestAnimationFrame(tick);
}

function syncAnimation() {
    cancelAnimationFrame(animation);
    animation = 0;
    previousTime = 0;
    if (canAnimate()) animation = requestAnimationFrame(tick);
}

function readMotionPreference() {
    reducedMotion.value = motionPreference?.matches ?? false;
    syncAnimation();
}

function togglePause() {
    paused.value = !paused.value;
    syncAnimation();
}

function focusLight() {
    if (!light.value?.matches(":focus-visible")) return;
    keyboardFocus = true;
    // A keyboard user always starts on an exposed part of the ring.
    phase = 4.05;
    paint();
    syncAnimation();
}

function blurLight() {
    keyboardFocus = false;
    syncAnimation();
}

function relocate() {
    if (jumpLocked || !light.value) return;
    const destination = (phase + 1.2 + Math.random() * 2.5) % turn;
    if (reducedMotion.value) {
        phase = destination;
        paint();
        return;
    }
    jumpLocked = true;
    jumping.value = true;
    burst.value = {
        left: light.value.style.left,
        top: light.value.style.top,
        zIndex: light.value.style.zIndex,
        key: Date.now(),
    };
    syncAnimation();
    jumpTimer = setTimeout(() => {
        phase = destination;
        paint();
        jumping.value = false;
        syncAnimation();
        settleTimer = setTimeout(() => {
            jumpLocked = false;
        }, 260);
    }, 190);
}

onMounted(() => {
    paint();
    motionPreference = window.matchMedia("(prefers-reduced-motion: reduce)");
    readMotionPreference();
    motionPreference.addEventListener("change", readMotionPreference);
    document.addEventListener("visibilitychange", syncAnimation);
    observer = new IntersectionObserver(([entry]) => {
        inView = entry?.isIntersecting ?? false;
        syncAnimation();
    });
    if (frame.value) observer.observe(frame.value);
});

onBeforeUnmount(() => {
    cancelAnimationFrame(animation);
    clearTimeout(jumpTimer);
    clearTimeout(settleTimer);
    observer?.disconnect();
    motionPreference?.removeEventListener("change", readMotionPreference);
    document.removeEventListener("visibilitychange", syncAnimation);
});
</script>

<template>
    <div
        ref="frame"
        class="about-portrait-frame"
        :class="{
            'orbit-paused': paused || reducedMotion,
            'orbit-jumping': jumping,
        }"
    >
        <svg
            class="portrait-orbit"
            viewBox="0 0 1000 1000"
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            <defs>
                <clipPath :id="portraitClip" clipPathUnits="objectBoundingBox">
                    <!-- Preserve the head above the ring; let its lower arc trim the torso. -->
                    <rect x="0" y="0" width="1" height="0.485" />
                    <ellipse
                        cx="0.5"
                        cy="0.485"
                        rx="0.48"
                        ry="0.445"
                        transform="rotate(-16 0.5 0.485)"
                    />
                </clipPath>
                <clipPath :id="lowerRingClip">
                    <rect x="0" y="485" width="1000" height="515" />
                </clipPath>
                <clipPath :id="upperRingClip">
                    <rect x="0" y="0" width="1000" height="485" />
                </clipPath>
                <linearGradient
                    :id="trailGradient"
                    ref="gradient"
                    gradientUnits="userSpaceOnUse"
                >
                    <stop offset="0" stop-color="#e3b45d" stop-opacity="0" />
                    <stop
                        offset="0.55"
                        stop-color="#e3b45d"
                        stop-opacity="0.12"
                    />
                    <stop offset="1" stop-color="#f3ce85" stop-opacity="0.85" />
                </linearGradient>
                <filter
                    :id="trailBlur"
                    x="-100%"
                    y="-100%"
                    width="300%"
                    height="300%"
                    color-interpolation-filters="sRGB"
                >
                    <feGaussianBlur stdDeviation="4" />
                </filter>
            </defs>
            <ellipse
                class="portrait-ring"
                cx="500"
                cy="485"
                rx="480"
                ry="445"
                transform="rotate(-16 500 485)"
            />
            <g :clip-path="`url(#${upperRingClip})`">
                <g
                    ref="trail"
                    class="portrait-trail"
                    :stroke="`url(#${trailGradient})`"
                >
                    <path
                        class="portrait-trail-haze"
                        :filter="`url(#${trailBlur})`"
                    />
                    <path class="portrait-trail-filament" />
                </g>
            </g>
        </svg>
        <button
            ref="light"
            type="button"
            class="portrait-light"
            aria-label="Reubicar luz en órbita"
            title="Toca la luz para moverla"
            @click="relocate"
            @focus="focusLight"
            @blur="blurLight"
        >
            <span class="portrait-light-core"></span>
        </button>
        <span
            v-if="burst"
            :key="burst.key"
            class="portrait-light-burst"
            :style="{ left: burst.left, top: burst.top, zIndex: burst.zIndex }"
            aria-hidden="true"
            @animationend="burst = null"
        ></span>
        <img
            :src="src"
            :alt="alt"
            :style="{ clipPath: `url(#${portraitClip})` }"
            width="1122"
            height="1402"
            fetchpriority="high"
        />
        <svg
            class="portrait-orbit portrait-orbit-front"
            viewBox="0 0 1000 1000"
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            <g :clip-path="`url(#${lowerRingClip})`">
                <ellipse
                    class="portrait-ring"
                    cx="500"
                    cy="485"
                    rx="480"
                    ry="445"
                    transform="rotate(-16 500 485)"
                />
                <g
                    ref="frontTrail"
                    class="portrait-trail"
                    :stroke="`url(#${trailGradient})`"
                >
                    <path
                        class="portrait-trail-haze"
                        :filter="`url(#${trailBlur})`"
                    />
                    <path class="portrait-trail-filament" />
                </g>
            </g>
        </svg>
        <button
            type="button"
            class="portrait-motion-toggle"
            :aria-label="
                reducedMotion
                    ? 'Órbita detenida por tu preferencia de movimiento reducido'
                    : paused
                      ? 'Reanudar órbita'
                      : 'Pausar órbita'
            "
            :title="paused ? 'Reanudar órbita' : 'Pausar órbita'"
            :aria-pressed="paused || reducedMotion"
            :disabled="reducedMotion"
            @click="togglePause"
        >
            <component
                :is="paused || reducedMotion ? PhPlay : PhPause"
                :size="14"
            />
        </button>
    </div>
</template>
