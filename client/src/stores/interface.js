import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useInterfaceStore = defineStore('interface', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of routers
    const interfaces = ref([])
    

    async function loadInterfaces(type){
        const response = await axiosApi({
            method: "GET",
            url: `routers/interfaces`,
            params: {
            type: type,
            }
        })
        interfaces.value = response.data.data;
    }

    const getInterfaces = (() => {
        return interfaces.value
    })


    return {
        loadInterfaces,
        getInterfaces
       }
})
