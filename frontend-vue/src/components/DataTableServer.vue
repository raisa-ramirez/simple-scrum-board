<script setup>
    import { ref } from 'vue'

    const itemsPerPage = 5
    const props = defineProps(['headers','serverItems','loading','loadItems',])
</script>
<template>
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="props.headers"
        :items="props.serverItems"
        :items-length="props.serverItems.length"
        :loading="props.loading"
        :search="props.search"
        @update:options="props.loadItems"
        loading-text="Loading... Please wait"
    ></v-data-table-server>
</template>