import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useInterfaceStore = defineStore('interface', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of interfaces
    const interfaces = ref([])
    

    async function loadInterfaces(identifier,type){
        
        await axiosApi.get('routers/interfaces',
        {
            params:{
                identifier: identifier.value,
                type: type.value
            }
        }).then((response) => {
            interfaces.value = response.data;

        })
       
    }

    const getInterfaces = (() => {
        return interfaces.value
    })


    return {
        loadInterfaces,
        getInterfaces
       }
})
