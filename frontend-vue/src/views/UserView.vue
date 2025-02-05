<script setup>
    import { ref } from 'vue'
    import DataTable from '@/components/DataTableServer.vue'
    import Button from '@/components/ButtonComponent.vue'
    import Input from '@/components/TextfieldComponent.vue'

    const title = 'Users'
    const headers = [
        {
            title: 'Username',
            align: 'start',
            sortable: false,
            key: 'username',
        }
    ]
    const search = ref('')
    const serverItems = ref([])
    const loading = ref(true)

    function loadItems () {
        // Do the fetch here
        serverItems.value = [
            { username: 'Raisa' },
            { username: 'Mireya' },
            { username: 'Cesia' }
        ]
        loading.value = false
    }
</script>
<template>
    <v-row class="ml-1 mt-1">
        <v-col>            
            <h2>
                <v-icon color="#8E24AA">mdi-lightning-bolt</v-icon> {{ title }}
            </h2>
        </v-col>
    </v-row> 
    <v-row class="pl-2 pr-2 pb-4">
        <v-col>
            <v-card class="mx-auto" border flat>
                <v-card-item class="pt-4 mt-4">
                    <v-row>
                        <v-col cols="9">
                            <Input :model="search" icon="mdi-magnify"/>
                        </v-col>
                        <v-col cols="3">
                            <Button text="Add user" icon="mdi-plus"/>
                        </v-col>
                    </v-row>                    
                </v-card-item>

                <v-divider></v-divider>

                <v-card-text class="text-medium-emphasis">
                    <DataTable 
                        :headers="headers" 
                        :serverItems="serverItems" 
                        :totalItems="totalItems" 
                        :loading="loading" 
                        :loadItems="loadItems"/>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row> 
</template>