<script setup lang="ts">
defineProps<{ errors: Record<string, string> }>();
</script>
<template>
    <div v-if="Object.keys(errors).length" class="form-errors" role="alert">
        <p>Revisa los siguientes datos:</p>
        <ul>
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>
