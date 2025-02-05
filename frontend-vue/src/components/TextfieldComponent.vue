<script setup>
    const props = defineProps(['model', 'icon'])
</script>
<template>
    <v-text-field
        :v-model="model"
        :prepend-inner-icon="icon"
        density="compact"        
        variant="outlined"
        color="#CE93D8"
        clearable
        rounded
    ></v-text-field>
</template>