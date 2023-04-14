import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useBridgeStore = defineStore('bridge', () => {
    // Axios
    const axiosApi = inject('axiosApi')
    const notyf = inject('notyf') 
    
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
            notyf.success('The bridge was created with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
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
            notyf.success('The port was created with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    async function createBridges(data){
        
        await axiosApi.put('bridges', data).then((response) => {
            
            notyf.success('The bridge was created with success.')
            bridges.value.push(response.data)
                   
        }).catch((error) => {
            
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    async function createPorts(data){
        
        await axiosApi.put('bridges/ports', data).then((response) => {

            notyf.success('The port was created with success.')
            ports.value.push(response.data)

            
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
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
            
        notyf.success('The bridge was deleted with success.')
            
        }).catch((error) => {

            notyf.error(error.response.data + " (" + error.response.status + ")")
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

            notyf.success('The port was deleted with success.') 
            
        }).catch((error) => {

            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    async function editPorts(data){
        
        await axiosApi.patch('bridges/ports/'+data.get('port_identity'),data).then((response) => {
            
            notyf.success('The port was edited with success.') 
                   
        }).catch((error) => {
            
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }


    async function editBridges(data){
        
        await axiosApi.patch('bridges/'+data.get('bridge_identity'),data).then((response) => {
            
            notyf.success('The bridge was edited with success.') 
                   
        }).catch((error) => {
            
            notyf.error(error.response.data + " (" + error.response.status + ")")
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
        getPorts,
        deleteBridges,
        deletePorts,
        editBridges,
        editPorts

       }
})
