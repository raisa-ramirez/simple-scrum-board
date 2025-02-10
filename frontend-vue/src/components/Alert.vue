<script setup>
    import { ref, watchEffect } from 'vue'
    import { useAlertStore } from '@/stores/alert'
    import { storeToRefs } from 'pinia'

    const store = useAlertStore()
    const { type, message, state } = storeToRefs(store) 
    const alert = ref({})

    watchEffect(() => {
      if(type.value == 'info'){
        alert.value.icon= 'mdi-information'
        alert.value.color= 'info'
      } else if(type.value == 'error'){
        alert.value.icon= 'mdi-skull'
        alert.value.color= 'error'
      }
      setTimeout(() => { state.value = false }, 3500);
    })
</script>
<template>
    <v-dialog   
        v-model="state"   
        width="auto" 
        transition="dialog-top-transition"       
    >
      <v-list
        class="py-2"
        color="primary"
        elevation="12"
        rounded="lg"
      >
        <v-list-item
          :prepend-icon="alert.icon"
          :title="message"
        >
          <template v-slot:prepend>
            <div class="pe-4">
              <v-icon :color="alert.color" size="x-large"></v-icon>
            </div>
          </template>
        </v-list-item>
      </v-list>
    </v-dialog>
</template>