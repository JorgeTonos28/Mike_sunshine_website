<script setup lang="ts">
import { useId } from "vue";

defineProps<{ src: string; alt: string }>();
const id = useId();
const portraitClip = `portrait-clip-${id}`;
const lowerRingClip = `portrait-ring-${id}`;
</script>

<template>
    <div class="about-portrait-frame">
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
            </defs>
            <ellipse
                class="portrait-ring"
                cx="500"
                cy="485"
                rx="480"
                ry="445"
                transform="rotate(-16 500 485)"
            />
        </svg>
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
            </g>
        </svg>
    </div>
</template>
