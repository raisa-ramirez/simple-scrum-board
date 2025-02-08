<script setup>
    const props = defineProps(['color','icon'])
</script>
<template>
    <v-btn 
    :color="props.color" 
    :icon="props.icon" 
    density="comfortable"     
    class="mr-1 custom-button"></v-btn>
</template>