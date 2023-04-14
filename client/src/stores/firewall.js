import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useFirewallStore = defineStore('firewall', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of interfaces
    const rules = ref([])
    

    async function loadRules(identifier){
       
        await axiosApi.get('firewalls',
        {
            params:{
                identifier: identifier.value,
            }
        }).then((response) => {
            rules.value = response.data;

        })
       
    }


    async function createRules(data){
        
        await axiosApi.put('firewalls', data).then((response) => {

            if(response.data!=false){
                rules.value.push(response.data)

                return "true"
            }
            else{
                
                return "false"
            }
                   
        }).catch((error) => {

            return "false"
        })
       
    }

    async function deleteRules(rule){
        
        await axiosApi.delete('firewalls/'+rule['.id'],{
            params:{
                rule: rule,
                identity: rule.router
            }
        }).then((response) => {
           
        let i = rules.value.findIndex(element => element['.id'] === rule['.id'])

        if (i >= 0) rules.value.splice(i, 1);
            
           return "true" 
            
        }).catch((error) => {

            return "false"
        })
       
        
    }

    async function editRules(data){
        
        await axiosApi.put('firewalls/'+data.get('rule_identity'),data).then((response) => {
            
            return "true"
                   
        }).catch((error) => {
            
            return "false"
        })
       
    }

    const getRules = (() => {
        return rules.value
    })



    return {
        createRules,
        loadRules,
        getRules,
        deleteRules,
        editRules

       }
})
