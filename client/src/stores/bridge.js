import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useBridgeStore = defineStore('bridge', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of interfaces
    const bridges = ref([])
    const ports= ref([])
    

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

    async function createBridges(data){
        

        await axiosApi.put('bridges', data).then((response) => {
            
            if(response.data!=false){
                bridges.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                 
            
        }).catch((error) => {
            console.log("error creating bridges!")
            return "false"
        })
       
    }

    async function createPorts(data){
        
        await axiosApi.put('bridges/ports', data).then((response) => {
            console.log(response.data)
            if(response.data!=false){
                ports.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                 
            
        }).catch((error) => {
            console.log("error creating ports!")
            return "false"
        })
       
    }

    async function deleteBridges(bridge){
        
        await axiosApi.delete('bridges/'+bridge['.id'],{
            params:{
                bridge: bridge,
                identity: bridge.router
            }
        }).then((response) => {
           
        let i = bridges.value.findIndex(element => element['.id'] === bridge['.id'])

        if (i >= 0) bridges.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deletePorts(port){
        
        await axiosApi.delete('bridges/ports/'+port['.id'],{
            params:{
                port: port,
                identity: port.router
            }
        }).then((response) => {
            let i = ports.value.findIndex(element => element['.id'] === port['.id'])

            if (i >= 0) ports.value.splice(i, 1);
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
    }


    const getBridges = (() => {
        return bridges.value
    })

    const getPorts = (() => {
        return ports.value
    })

    const getNotyfValue = (() => {
        return notyf_value.value
    })

    return {
        createBridges,
        createPorts,
        loadBridges,
        loadPorts,
        getBridges,
        getPorts,
        getNotyfValue,
        deleteBridges,
        deletePorts
       }
})
