import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useBridgeStore = defineStore('bridge', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of interfaces
    const bridges = ref([])
    const ports= ref([])
    
    async function createBridges(data){
        
        console.log(data)

        await axiosApi.put('bridges',data).then((response) => {
            bridges.value.push(response.data.data);
            return true;
        }).catch((error) => {
            return false
        })
       
    }

    async function createPorts(data){
        
        await axiosApi.put('bridges/ports',
        {
            params:{
                data: data,
            }
        }).then((response) => {
            ports.value.push(response.data.data);
            return true;
        }).catch((error) => {
            return false
        })
       
    }

    async function loadBridges(identifier){
        
        await axiosApi.get('bridges',
        {
            params:{
                identifier_bridges: identifier.value,
            }
        }).then((response) => {
            bridges.value = response.data;

        })
       
    }

    async function loadPorts(identifier){
        
        await axiosApi.get('bridges/ports',
        {
            params:{
                identifier_ports: identifier.value,
            }
        }).then((response) => {
            ports.value = response.data;

        })
       
    }


    const getBridges = (() => {
        return bridges.value
    })

    const getPorts = (() => {
        return ports.value
    })


    return {
        createBridges,
        createPorts,
        loadBridges,
        loadPorts,
        getBridges,
        getPorts
       }
})
