import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useFirewallStore = defineStore('firewall', () => {
    // Axios
    const axiosApi = inject('axiosApi')
    const notyf = inject('notyf') 

    // Array of interfaces
    const rules = ref([])
    

    async function loadRules(identifier,active_routers){

        await axiosApi.get('firewalls',
        {
            params:{
                identifier: identifier.value,
                active_routers: active_routers
            }
        }).then((response) => {
            rules.value = response.data;

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    async function createRules(data){

        await axiosApi.put('firewalls', data).then((response) => {

        if(data.get('identity')=='all'){
            response.data.forEach(element => rules.value.push(element))
        }
        else{
            rules.value.push(response.data)
        }
        
    
        notyf.success('The firewall rule was added with success.')
                   
        }).catch((error) => {

            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
    }

    async function deleteRules(rule){
        
        await axiosApi.delete('firewalls/'+rule['.id'],{
            params:{
                rule: rule,
                identity: rule.router
            }
        }).then((response) => {
           
        notyf.success('The firewall rule was deleted with success.')    

        let i = rules.value.findIndex(element => element['.id'] === rule['.id'])

        if (i >= 0) rules.value.splice(i, 1)
            
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
       
        
    }

    async function editRules(data){
        
        await axiosApi.put('firewalls/'+data.get('rule_identity'),data).then((response) => {
            
            notyf.success('The firewall rule was edited with success.')
                   
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
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
