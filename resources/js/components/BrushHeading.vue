<script setup lang="ts">
defineProps<{ title: string; large?: boolean }>();
</script>
<template>
    <div class="brush-heading" :class="{ large }">
        <h1 v-if="large">{{ title }}</h1>
        <h2 v-else>{{ title }}</h2>
        <img
            src="/images/design/brush-gold.png"
            alt=""
            aria-hidden="true"
            width="240"
            height="28"
        />
    </div>
</template>
