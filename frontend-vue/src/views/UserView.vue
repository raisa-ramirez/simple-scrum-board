<script setup>
    import { ref, onMounted } from 'vue'
    import { useVuelidate } from '@vuelidate/core'
    import { email, required } from '@vuelidate/validators'
    import DataTable from '@/components/DataTableServer.vue'
    import Button from '@/components/Button.vue'
    import Input from '@/components/Textfield.vue'
    import ActionButton from '@/components/ActionButton.vue'
    import Select from '@/components/Select.vue'   
    import Alert from '@/components/Alert.vue'
    import apiService from '@/services/api.js'
    import { useAlertStore } from '@/stores/alert'

    const title = 'Users page'
    const modalTitle = ref('Add user')
    const headers = [
        { title: 'Username', sortable: true, key: 'name' },
        { title: 'Email', sortable: true, key: 'email' },
        { title: 'Actions', sortable: false, key: 'actions' }
    ]
    const search = ref('')
    const serverItems = ref([])
    const loading = ref(true)
    const itemsPerPage = ref(5)
    const totalItems = ref(0)
    const dialog = ref(false)
    const roles = ref([])
    const option = ref('')
    const fields = ref({
        'name': '',
        'email': '',
        'role_id': '',
        'password': ''
    })    

    const rules = {
      name: { required },
      email: { required, email },
      role_id: { required },
      password: (option.value=='add')?{ required }:{}      
    }

    const v$ = useVuelidate(rules, fields)

    const store = useAlertStore()
    const { showAlert } = store

    const loadItems = async ({ itemsPerPage, page=1, sortBy="", search="" }) => { 
        loadItems.value = true 
        let params = {
            itemsPerPage: itemsPerPage,
            page: page,            
            sortBy: sortBy,
            search: search
        }

        await apiService.getData(`user`, params)
        .then((response) => {
            serverItems.value = response.data.data.data
            totalItems.value = response.data.data.total
            loading.value = false
        })    
        .catch((error) => {
            showAlert('error', error)
            loading.value = false
        })    
    }    

    const openModal = (action ='add', item = null) => {
        dialog.value = true
        option.value = action
        fields.value = {}
        modalTitle.value = 'Add user'
        if(action == 'edit'){
            fields.value = {...item}
            modalTitle.value = 'Edit user'
        }        
    }

    const save = async () => { 
        let validate = await v$.value.$validate()

        if(!validate){
            showAlert('error', 'Complete the form')
            return;
        }
     
        if(option.value == 'add'){
            create()
        } else if(option.value == 'edit'){
            edit()
        }        
    }

    const create = async () => {
        await apiService.storeData('user', fields.value)
        .then((response) => {
            showAlert('info', response.data.message)  
            reload()         
        })
        .catch((error) => {
            showAlert('error', error)
            reload() 
        })
    }

    const edit = async () => {
        await apiService.updateData('user', fields.value.id, fields.value)
        .then((response) => {
            showAlert('info', response.data.message) 
            reload()             
        })
        .catch((error) => {
            showAlert('error', error) 
            reload() 
        })
    }

    const destroy = async (item) => {
        await apiService.deleteData('user',item.id)
        .then((response)=>{
            showAlert('info', response.data.message) 
            reload() 
        })
        .catch((error)=>{
            showAlert('error', error) 
            reload() 
        })
    }   

    const start = async() => {
        let [lstRoles] = await apiService.getAll([
            apiService.getData('roles'),
        ])
        roles.value = lstRoles.data.data        
    }

    const reload = () => {
        dialog.value = false
        loadItems(itemsPerPage)
        fields.value = {}
    }

    onMounted(() => {
        start()
    })    
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
                        <v-col cols="12" lg="9" md="9" sm="12">
                            <Input v-model="search" icon="mdi-magnify" placeholder="Search by username" autofocus/>
                        </v-col>
                        <v-col cols="12" lg="3" md="3" sm="12">
                            <Button text="Add user" icon="mdi-plus" @click="openModal()"></Button>
                        </v-col>
                    </v-row>                    
                </v-card-item>

                <v-card-text class="text-medium-emphasis">
                    <!-- User's table -->
                    <DataTable 
                        :itemsPerPage="itemsPerPage"
                        :headers="headers" 
                        :serverItems="serverItems" 
                        :totalItems="totalItems" 
                        :loading="loading" 
                        :search="search"
                        :loadItems="loadItems">
                        <template v-slot="item">
                            <ActionButton color="#8E24AA" icon="mdi-pencil" @click="openModal('edit', item.item)"></ActionButton>
                            <ActionButton color="error" icon="mdi-delete" @click="destroy(item.item)"></ActionButton>
                        </template>
                    </DataTable>
                    <!-- User's table -->
                </v-card-text>
            </v-card>
        </v-col>
    </v-row> 

    <!-- User form -->
    <v-dialog
      v-model="dialog"
      max-width="700"
    >
      <v-card
        prepend-icon="mdi-account"
        color="#F3E5F5"
        :title="modalTitle"
      >
        <v-card-text>
          <v-row dense>
            <v-col cols="12" md="6" sm="12">
                <Input icon="mdi-cursor-text" label="Username" v-model="fields.name" autofocus></Input>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <Input icon="mdi-cursor-text" label="Email" v-model="fields.email"></Input>
            </v-col>
            <v-col cols="12" md="6" sm="12" v-if="option=='add'">
                <Input icon="mdi-cursor-text" label="Password" v-model="fields.password" type="password"></Input>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <Select :items="roles" label="Role" v-model="fields.role_id"></Select>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <Button text="Close" variant="tonal" @click="dialog = false"></Button>
          <Button text="Save" @click="save('add')"></Button>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- User form -->

    <!-- Enables alerts display -->
    <Alert/>
    <!-- Enables alerts display -->
</template>