<script setup>
    const props = defineProps(['icon'])
</script>
<template>
    <v-text-field
        :prepend-inner-icon="icon"
        density="compact"        
        variant="outlined"
        color="#CE93D8"
        clearable
        rounded
    ></v-text-field>
</template>