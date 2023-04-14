<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useInterfaceStore } from "../../../stores/interface.js"
import { useRouter } from 'vue-router'

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const interfaceStore = useInterfaceStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()
import OspfTemplateEdit from "./OSPFTemplateEdit.vue"

var router_ospf = ref("all")
var selected_ospf= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const loadTemplatesOSPF = (() => { routingStore.loadTemplatesOSPF(router_ospf) })
const ospftemplates = computed(() => { return routingStore.getTemplatesOSPF() })


const loadAreasOSPF = (() => { routingStore.loadAreasOSPF(router_ospf) })
const ospfareas = computed(() => { return routingStore.getAreasOSPF() })

const loadInterfaces = (() => { interfaceStore.loadInterfaces(router_ospf,type_all) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const type_all = ref("all")


const deleteTemplatesOSPF = (ospftemplate) => {
    
    routingStore.deleteTemplatesOSPF(ospftemplate)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
}


const showTemplatesOSPF = (ospftemplate) => {
   
    if(ospftemplate.interfaces!=undefined && ospftemplate.interfaces!="" && !(ospftemplate.interfaces instanceof Array)){
        ospftemplate.interfaces = ospftemplate.interfaces.split(',')
    }
    
    if(ospftemplate.interfaces==undefined ||ospftemplate.interfaces=="" && !(ospftemplate.interfaces instanceof Array)){
        ospftemplate.interfaces = []
    }


    loadAreasOSPF()
    loadInterfaces()
    selected_ospf.value = ospftemplate
    
}

watch(router_ospf, () => {
    loadTemplatesOSPF()
})

onBeforeMount(() => {
   
    loadRouters()
    loadTemplatesOSPF()

})
</script>

<template>
   <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">OSPF</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">OSPF Interface-Templates</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'OSPFTemplateCreate'}"
                                                    :title="`Create OSPF interface-template`">
                                    <button class="btn btn-primary">Add OSPF Interface-Template</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                        <select class="custom-select custom-select-lg" v-model="router_ospf">
                            <option value="all" selected>All</option>
                            <option :value="router.id" v-for="router in routers">{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Interfaces</th>
                                        <th>Area</th>
                                        <th>Networks</th>
                                        <th>Network Type</th>
                                        <th>Cost</th>
                                        <th>Priority</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                      
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ospftemplates.length==0">
                                        <td colspan="10" class="text-center" style="height:55px!important;">There are no OSPF Interfaces-Templates.</td>
                                    </tr>
                                    <tr v-for="ospftemplate in ospftemplates">
                                  
                                        <td>{{ospftemplate['.id'].substring(1)}}</td>
                                        <td>#{{ospftemplate.router}}</td>

                                        <td v-if="ospftemplate.interfaces==undefined || ospftemplate.interfaces==''">-</td>
                                        <td v-else>{{ospftemplate.interfaces}}</td>

                                        <td v-if="ospftemplate.area==undefined">-</td>
                                        <td v-else>{{ospftemplate.area}}</td>

                                        <td v-if="ospftemplate.networks==undefined">-</td>
                                        <td v-else>{{ospftemplate.networks}}</td>

                                        <td v-if="ospftemplate.type==undefined">-</td>
                                        <td v-else>{{ospftemplate.type}}</td>

                                        <td v-if="ospftemplate.cost==undefined">-</td>
                                        <td v-else>{{ospftemplate.cost}}</td>

                                        <td v-if="ospftemplate.priority==undefined">-</td>
                                        <td v-else>{{ospftemplate.priority}}</td>

                                        <td class="text-success" v-if="ospftemplate.disabled==undefined">false</td>
                                        <td class="text-success" v-if="ospftemplate.disabled=='false'">{{ospftemplate.disabled}}</td>
                                        <td class="text-danger" v-if="ospftemplate.disabled=='true'">{{ospftemplate.disabled}}</td>

                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteTemplatesOSPF(ospftemplate)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showTemplatesOSPF(ospftemplate)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        

                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

               <ospf-template-edit :ospftemplate="selected_ospf" :ospfareas="ospfareas" :interfaces="interfaces" v-if="selected_ospf"></ospf-template-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
