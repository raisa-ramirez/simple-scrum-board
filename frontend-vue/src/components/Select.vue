<script setup>
    const props = defineProps(['items'])
</script>
<template>
    <v-select
    :items="items"
    item-title="name"
    item-value="id"
    density="compact"        
    variant="outlined"
    color="#CE93D8"
    clearable
    rounded
    chips
    ></v-select>
</template>