<script setup>
    import { ref, onMounted, watch } from 'vue'
    import { useAlertStore } from '@/stores/alert'
     import { useVuelidate } from '@vuelidate/core'
    import { required } from '@vuelidate/validators'
    import Select from '@/components/Select.vue'
    import Button from '@/components/Button.vue'
    import Input from '@/components/Textfield.vue'
    import Alert from '@/components/Alert.vue'
    import apiService from '@/services/api'
    import draggable from 'vuedraggable'  

    const title = 'My Task'
    const modalTitle = 'Add task'
    const dialog = ref(false)
    const store = useAlertStore();
    const { showAlert } = store
    const categories = ref([])
    const formCategories = ref([])
    const category = ref(null)
    const states = ref([
        { id: 1, name: 'To do'},
        { id: 2, name: 'In Progress'},
        { id: 3, name: 'Done'}
    ])
    const fields = ref({
        title: null,
        description: null,
        state_id: 1,
        category_id: null
    })
    const tasksTodo = ref([]) 
    const tasksInprogress = ref([]) 
    const tasksDone = ref([])   
    
    const rules = {
      title: { required },
      description: { required },
      state_id: { required },
      category_id: { required }     
    }

    const v$ = useVuelidate(rules, fields)

    const start = async () => {  
        try {
            let [ lstCategories, lstTasks ] = await apiService.getAll([
                apiService.getBy('categoriesByUser', 2), // Replace by current user
                apiService.getBy('tasksByCategory', 0)
            ])
            
            formCategories.value = [...lstCategories.data.data]
            categories.value = [...lstCategories.data.data]
            categories.value.push({id:0, name:'All categories'})
            category.value = 0
            if(categories.value.length>0){
                filterTasks(lstTasks.data.data)
            }   
        } catch (error) {
            showAlert('error',error)
        }     
    }

    const filterTasks = (lstTasks) => {        
        tasksTodo.value = lstTasks.filter((task) => task.state_id==1)
        tasksInprogress.value = lstTasks.filter((task) => task.state_id==2)
        tasksDone.value = lstTasks.filter((task) => task.state_id==3)
    }

    const showTasks = async () => {
        console.log(category.value)
        await apiService.getBy('tasksByCategory', category.value)
        .then((response) => {
            filterTasks(response.data.data)
        })
        .catch((error) => {
            showAlert('error',error)
        })
    } 
    
    const changeList = (evt, id) => {
        let added = 'added' in evt
        if(added){
            let element = evt.added.element
            updateTaskState(element, id)
        }
    }  

    const updateTaskState = async (element, stateId) => {  
        let taskId = element.id
        let task = {
            state_id : stateId
        }
        await apiService.updateData('updateState', taskId, task)
        .then((response) => {
            showAlert('info',`Task's state has been updated`)
        })
        .catch((error) => {
            showAlert('error',error)
        })
    }

    const save = async () => {
        let validate = await v$.value.$validate()

        if(!validate){
            showAlert('error', 'Complete the form')
            return;
        }

        await apiService.storeData('task', fields.value)
        .then((response) => {
            showAlert('info', response.data.message)  
            showTasks()
            dialog.value = false
            fields.value = {}
            fields.value.state_id = 1
        })
        .catch((error) => {
            showAlert('error', error)
            showTasks()
            dialog.value = 
            fields.value = {}
            fields.value.state_id = 1
        })
    }

    watch(category, (newCategory) => {
        showTasks(newCategory)
    })

    onMounted(() => {
        start()
    })    
</script>
<template>
    <v-row class="ml-1 mt-1">
        <v-col>            
            <h2>
                <v-icon style="color:var(--custom-base-color)">mdi-lightning-bolt</v-icon> {{ title }}
            </h2>
        </v-col>
    </v-row> 

    <v-row class="pl-5 pr-5">
        <v-col cols="12" lg="4" md="6" sm="12">
            <Select label="Choose a category" v-model="category" :items="categories" :clearable="false"></Select>
        </v-col>
        <v-col cols="12" lg="4" md="6" sm="12">
            <Button text="Add task" icon="mdi-checkbox-marked-circle-plus-outline" @click="dialog=true"></Button>
        </v-col>
    </v-row> 

    <v-row class="pl-2 pr-2 pb-4">
        <v-col>
            <v-card class="mx-auto" variant="text">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" lg="4" md="4" sm="12">
                            <h3 class="bg-blue-grey-darken-2 text-center pt-2 pb-2 rounded-xl mb-4">To do</h3>
                            <draggable v-model="tasksTodo" class="dragArea list-group" group="all" item-key="id" @change="changeList($event, 1)">
                                <template #item="{ element: task }">
                                <v-card class="mx-auto mb-4" variant="tonal">
                                    <template v-slot:subtitle>
                                        <span class="font-weight-light">
                                            <v-chip color="#493D9E" variant="flat" size="x-small">
                                                {{ task.category.name }}
                                            </v-chip>
                                        </span>
                                    </template>
                                    <template v-slot:title>
                                        <span class="font-weight-black">{{ task.title }}</span>
                                    </template>
                                    <v-card-text class="bg-blue-grey-lighten-4 pt-4">{{ task.description }}</v-card-text>
                                </v-card>
                                </template>
                            </draggable>
                        </v-col>

                        <v-col cols="12" lg="4" md="4" sm="12">
                            <h3 class="bg-blue-grey-darken-2 text-center pt-2 pb-2 rounded-xl mb-4">In Progress</h3>
                            <draggable v-model="tasksInprogress" class="dragArea list-group" group="all" item-key="id" @change="changeList($event, 2)">
                                <template #item="{ element: task }">
                                <v-card class="mx-auto mb-4" variant="tonal">
                                    <template v-slot:subtitle>
                                        <span class="font-weight-light">
                                            <v-chip color="#493D9E" variant="flat" size="x-small">
                                                {{ task.category.name }}
                                            </v-chip>
                                        </span>
                                    </template>
                                    <template v-slot:title>
                                        <span class="font-weight-black">{{ task.title }}</span>
                                    </template>
                                    <v-card-text class="bg-blue-grey-lighten-4 pt-4">{{ task.description }}</v-card-text>
                                </v-card>
                                </template>
                            </draggable>
                        </v-col>

                        <v-col cols="12" lg="4" md="4" sm="12">
                            <h3 class="bg-blue-grey-darken-2 text-center pt-2 pb-2 rounded-xl mb-4">Done</h3>
                            <draggable v-model="tasksDone" class="dragArea list-group" group="all" item-key="id" @change="changeList($event, 3)">
                                <template #item="{ element: task }">
                                <v-card class="mx-auto mb-4" variant="tonal">
                                    <template v-slot:subtitle>
                                        <span class="font-weight-light">
                                            <v-chip color="#493D9E" variant="flat" size="x-small">
                                                {{ task.category.name }}
                                            </v-chip>
                                        </span>
                                    </template>
                                    <template v-slot:title>
                                        <span class="font-weight-black">{{ task.title }}</span>
                                    </template>
                                    <v-card-text class="bg-blue-grey-lighten-4 pt-4">{{ task.description }}</v-card-text>
                                </v-card>
                                </template>
                            </draggable>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <!-- Task form -->
    <v-dialog
      v-model="dialog"
      max-width="700"
    >
      <v-card
        prepend-icon="mdi-checkbox-marked-circle-plus-outline"
        color="#F3E5F5"
        :title="modalTitle"
      >
        <v-card-text>
          <v-row dense>
            <v-col cols="12" md="6" sm="12">
                <Input icon="mdi-cursor-text" label="Title" v-model="fields.title" autofocus></Input>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <Input icon="mdi-cursor-text" label="Description" v-model="fields.description"></Input>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <Select :items="states" label="Task state" v-model="fields.state_id"></Select>
            </v-col>
            <v-col cols="12" md="6" sm="12">
                <Select :items="formCategories" label="Category" v-model="fields.category_id"></Select>
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
    <!-- Task form -->

    <!-- Enables alerts display -->
    <Alert/>
    <!-- Enables alerts display -->
</template>