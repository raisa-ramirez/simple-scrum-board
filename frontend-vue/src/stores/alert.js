import { defineStore } from "pinia";
import { ref } from 'vue'

export const useAlertStore = defineStore('alerts', () => {
    const state = ref(false)
    const type = ref('')
    const message = ref('')

    const showAlert = (typeValue, messageValue) => {
        state.value = true        
        type.value = typeValue
        message.value = messageValue  
    }

    return {
        showAlert,
        type,
        message,
        state
    }
})