import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useInterfaceStore = defineStore('interface', () => {
    // Axios
    const axiosApi = inject('axiosApi')
    const notyf = inject('notyf') 

    // Array of interfaces
    const interfaces = ref([])
    const interfacesEtherWlan = ref([])
    

    async function loadInterfaces(identifier,type){

        
        await axiosApi.get('routers/interfaces',
        {
            params:{
                identifier: identifier.value,
                type: type.value
            }
        }).then((response) => {
            interfaces.value = response.data;
            
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    const getInterfaces = (() => {
        return interfaces.value
    })

    const getEtherWlanIfaces = (() => {
        
        interfaces.value.forEach((element,index) => {
            
            if(element.type=="ether" || element.type=="wlan"){
                
                interfacesEtherWlan.value.push(element)
          
            }
        })
        
        return interfacesEtherWlan.value
    })


    return {
        loadInterfaces,
        getInterfaces,
        getEtherWlanIfaces
       }
})
