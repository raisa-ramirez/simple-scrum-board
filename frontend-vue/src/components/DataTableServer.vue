<script setup>
    const props = defineProps([
        'headers','serverItems','loading',
        'loadItems','search','totalItems'
    ])    
    const itemsPerPage = defineModel('itemsPerPage')
    const itemsPerPageOptions = [
        { title: "5", value: 5 },
        { title: "10", value: 10 },
        { title: "50", value: 50 },
        { title: "100", value: 100 },
        { title: "All", value: props.totalItems }
    ]
</script>
<template>
    <v-data-table-server
        v-model:items-per-page="itemsPerPage"
        :headers="props.headers"
        :items="props.serverItems"
        :items-length="props.totalItems"
        :loading="props.loading"
        :search="props.search"
        @update:options="props.loadItems"
        :items-per-page-options="itemsPerPageOptions"
        loading-text="Loading... Please wait"  
        show-current-page
        hover
    >
        <template v-slot:item.actions="{ item }">
            <td>
                <slot :item="item"></slot>
            </td>
        </template>    
    </v-data-table-server>
</template>