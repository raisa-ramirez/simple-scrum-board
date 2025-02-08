<script setup>
    const props = defineProps(['text', 'icon'])
</script>
<template>
    <v-btn
        class="text-none custom-button"
        variant="elevated"
        rounded="xl"
        size="large"
        :text="text"
        :prepend-icon="icon"
    ></v-btn>
</template>