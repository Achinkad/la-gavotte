import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useRoutingStore = defineStore('routing', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of interfaces
    const bgp_connections = ref([])
    const ospf_instances = ref([])
    const ospf_areas = ref([])
    const ospf_templates = ref([])
    const rip_instances = ref([])
    const rip_templates = ref([])
    

    async function loadConnectionsBGP(identifier){
       
        await axiosApi.get('routing/bgp',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            bgp_connections.value = response.data;

        })
       
    }


    async function createConnectionsBGP(data){
        
        await axiosApi.put('routing/bgp', data).then((response) => {

            if(response.data!=false){
                bgp_connections.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteConnectionsBGP(bgpconnection){
        
        await axiosApi.delete('routing/bgp/'+bgpconnection['.id'],{
            params:{
                bgpconnection: bgpconnection,
                identity: bgpconnection.router
            }
        }).then((response) => {
           
        let i = bgp_connections.value.findIndex(element => element['.id'] === bgpconnection['.id'])

        if (i >= 0) bgp_connections.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editConnectionsBGP(data){
        
        await axiosApi.put('routing/bgp/'+data.get('bgp_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
    }

    const getConnectionsBGP = (() => {
        return bgp_connections.value
    })


    async function loadInstancesOSPF(identifier){
       
        await axiosApi.get('routing/ospf/instance',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            ospf_instances.value = response.data;

        })
       
    }


    async function createInstancesOSPF(data){
        
        await axiosApi.put('routing/ospf/instance', data).then((response) => {

            if(response.data!=false){
                ospf_instances.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteInstancesOSPF(ospfinstance){
        
        await axiosApi.delete('routing/ospf/instance/'+ospfinstance['.id'],{
            params:{
                ospfinstance: ospfinstance,
                identity: ospfinstance.router
            }
        }).then((response) => {
           
        let i = ospf_instances.value.findIndex(element => element['.id'] === ospfinstance['.id'])

        if (i >= 0) ospf_instances.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editInstancesOSPF(data){
        
        await axiosApi.put('routing/ospf/instance/'+data.get('ospf_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
        
    }

    const getInstancesOSPF = (() => {
        return ospf_instances.value
    })




    async function loadAreasOSPF(identifier){
       
        await axiosApi.get('routing/ospf/area',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            ospf_areas.value = response.data;

        })
       
    }


    async function createAreasOSPF(data){
        
        await axiosApi.put('routing/ospf/area', data).then((response) => {

            if(response.data!=false){
                ospf_areas.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteAreasOSPF(ospfarea){
        
        await axiosApi.delete('routing/ospf/area/'+ospfarea['.id'],{
            params:{
                ospfarea: ospfarea,
                identity: ospfarea.router
            }
        }).then((response) => {
           
        let i = ospf_areas.value.findIndex(element => element['.id'] === ospfarea['.id'])

        if (i >= 0) ospf_areas.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editAreasOSPF(data){
        
        await axiosApi.put('routing/ospf/area/'+data.get('ospf_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
        
    }

    const getAreasOSPF = (() => {
        return ospf_areas.value
    })



    async function loadTemplatesOSPF(identifier){
       
        await axiosApi.get('routing/ospf/template',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            ospf_templates.value = response.data;

        })
       
    }


    async function createTemplatesOSPF(data){
        
        await axiosApi.put('routing/ospf/template', data).then((response) => {

            if(response.data!=false){
                ospf_templates.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteTemplatesOSPF(ospftemplate){
        
        await axiosApi.delete('routing/ospf/template/'+ospftemplate['.id'],{
            params:{
                ospftemplate: ospftemplate,
                identity: ospftemplate.router
            }
        }).then((response) => {
           
        let i = ospf_templates.value.findIndex(element => element['.id'] === ospftemplate['.id'])

        if (i >= 0) ospf_templates.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editTemplatesOSPF(data){
        
        await axiosApi.put('routing/ospf/template/'+data.get('ospf_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
        
    }

    const getTemplatesOSPF = (() => {
        return ospf_templates.value
    })

    async function loadInstancesRIP(identifier){
       
        await axiosApi.get('routing/rip/instance',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            rip_instances.value = response.data;

        })
       
    }


    async function createInstancesRIP(data){
        
        await axiosApi.put('routing/rip/instance', data).then((response) => {

            if(response.data!=false){
                rip_instances.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteInstancesRIP(ripinstance){
        
        await axiosApi.delete('routing/rip/instance/'+ripinstance['.id'],{
            params:{
                ripinstance: ripinstance,
                identity: ripinstance.router
            }
        }).then((response) => {
           
        let i = rip_instances.value.findIndex(element => element['.id'] === ripinstance['.id'])

        if (i >= 0) rip_instances.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editInstancesRIP(data){
        
        await axiosApi.put('routing/rip/instance/'+data.get('rip_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
        
    }

    const getInstancesRIP = (() => {
        return rip_instances.value
    })

    async function loadTemplatesRIP(identifier){
       
        await axiosApi.get('routing/rip/template',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            rip_templates.value = response.data;

        })
       
    }


    async function createTemplatesRIP(data){
        
        await axiosApi.put('routing/rip/template', data).then((response) => {

            if(response.data!=false){
                rip_templates.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteTemplatesRIP(riptemplate){
        
        await axiosApi.delete('routing/rip/template/'+riptemplate['.id'],{
            params:{
                riptemplate: riptemplate,
                identity: riptemplate.router
            }
        }).then((response) => {
           
        let i = rip_templates.value.findIndex(element => element['.id'] === riptemplate['.id'])

        if (i >= 0) rip_templates.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editTemplatesRIP(data){
        
        await axiosApi.put('routing/rip/template/'+data.get('rip_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
        
    }

    const getTemplatesRIP = (() => {
        return rip_templates.value
    })

    return {
        createConnectionsBGP,
        loadConnectionsBGP,
        getConnectionsBGP,
        deleteConnectionsBGP,
        editConnectionsBGP,

        createInstancesOSPF,
        loadInstancesOSPF,
        getInstancesOSPF,
        deleteInstancesOSPF,
        editInstancesOSPF,

        createAreasOSPF,
        loadAreasOSPF,
        getAreasOSPF,
        deleteAreasOSPF,
        editAreasOSPF,

        createTemplatesOSPF,
        loadTemplatesOSPF,
        getTemplatesOSPF,
        deleteTemplatesOSPF,
        editTemplatesOSPF,

        createInstancesRIP,
        loadInstancesRIP,
        getInstancesRIP,
        deleteInstancesRIP,
        editInstancesRIP,

        createTemplatesRIP,
        loadTemplatesRIP,
        getTemplatesRIP,
        deleteTemplatesRIP,
        editTemplatesRIP

       }
})
