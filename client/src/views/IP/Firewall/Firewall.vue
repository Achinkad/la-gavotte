<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useFirewallStore } from "../../../stores/firewall.js"
import { useRouterStore } from "../../../stores/router.js"
import { useRouter } from 'vue-router'
import FirewallEdit from "./FirewallEdit.vue"

const firewallStore = useFirewallStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const router = useRouter()

var router_rules = ref("all")
var selected_rule= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadRules = (() => { firewallStore.loadRules(router_rules) })
const rules = computed(() => { return firewallStore.getRules() })



const deleteRule = (rule) => {
    
    firewallStore.deleteRules(rule)
    
}


const showRule = (rule) => {
   

    selected_rule.value = rule
    
}

watch(router_rules, () => {
    loadRules()
})

onBeforeMount(() => {
   
    loadRouters()
    loadRules()

})
</script>

<template>
   <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Rules</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Firewall Rules</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'FirewallCreate'}"
                                                    :title="`Create Rule`">
                                    <button class="btn btn-primary">Add Rule</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                        <select class="custom-select custom-select-lg" v-model="router_rules">
                            <option value="all" selected>All</option>
                            <option :value="router.id" v-for="router in routers">{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Action</th>
                                        <th>Chain</th>
                                        <th>Src-address</th>
                                        <th>Dst-address</th>
                                        <th>Src-port</th>
                                        <th>Dst-port</th>
                                        <th>Protocol</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="rules.length==0">
                                        <td colspan="11" class="text-center" style="height:55px!important;">There are no rules.</td>
                                    </tr>
                                    <tr v-for="rule in rules">
                                  
                                        <td>{{rule['.id'].substring(1)}}</td>
                                        <td>#{{rule.router}}</td>
                                        <td>{{rule.action}}</td>
                                        <td>{{rule.chain}}</td>

                                        <td v-if="rule['src-address']==undefined">-</td>
                                        <td v-else>{{rule['src-address']}}</td>

                                        <td v-if="rule['dst-address']==undefined">-</td>
                                        <td v-else>{{rule['dst-address']}}</td>

                                        <td v-if="rule['src-port']==undefined">-</td>
                                        <td v-else>{{rule['src-port']}}</td>

                                        <td v-if="rule['dst-port']==undefined">-</td>
                                        <td v-else>{{rule['dst-port']}}</td>

                                        <td v-if="rule.protocol==undefined">-</td>
                                        <td v-else>{{rule.protocol}}</td>

                                        <td class="text-success" v-if="rule.disabled==undefined">false</td>
                                        <td class="text-success" v-if="rule.disabled=='false'">{{rule.disabled}}</td>
                                        <td class="text-danger" v-if="rule.disabled=='true'">{{rule.disabled}}</td>
                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteRule(rule)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showRule(rule)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        
                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div >
                    </div>

               
                <firewall-edit :rule="selected_rule" v-if="selected_rule"></firewall-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
