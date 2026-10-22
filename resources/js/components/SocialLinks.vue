<script setup lang="ts">
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
    PhInstagramLogo,
    PhFacebookLogo,
    PhYoutubeLogo,
} from "@phosphor-icons/vue";
import type { Shared } from "../types";
defineProps<{ labels?: boolean }>();
const page = usePage<Shared>();
const links = computed(() =>
    [
        {
            name: "Instagram",
            url: page.props.studio.instagram_url,
            icon: PhInstagramLogo,
        },
        {
            name: "Facebook",
            url: page.props.studio.facebook_url,
            icon: PhFacebookLogo,
        },
        {
            name: "YouTube",
            url: page.props.studio.youtube_url,
            icon: PhYoutubeLogo,
        },
    ].filter((link) => link.url),
);
</script>
<template>
    <div class="studio-socials">
        <a
            v-for="link in links"
            :key="link.name"
            :href="link.url!"
            :aria-label="link.name"
            :target="link.url === '/#' ? undefined : '_blank'"
            rel="noopener noreferrer"
            ><component :is="link.icon" :size="23" weight="light" /><span
                v-if="labels"
                >{{ link.name }}</span
            ></a
        >
    </div>
</template>
