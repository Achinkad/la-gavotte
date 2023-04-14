<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useRouter } from 'vue-router'
import { useInterfaceStore } from "../../../stores/interface.js"

const interfaceStore = useInterfaceStore()
const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()
import RipTemplateEdit from "./RIPTemplateEdit.vue"

var router_rip = ref("all")
var selected_rip= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadTemplatesRIP = (() => { routingStore.loadTemplatesRIP(router_rip) })
const riptemplates = computed(() => { return routingStore.getTemplatesRIP() })

const loadInstancesRIP = (() => { routingStore.loadInstancesRIP(router_rip) })
const ripinstances = computed(() => { return routingStore.getInstancesRIP() })

const loadInterfaces = (() => { interfaceStore.loadInterfaces(router_rip,type_all) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const type_all = ref("all")

const deleteTemplatesRIP = (riptemplate) => {
    
    routingStore.deleteTemplatesRIP(riptemplate)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
}


const showTemplatesRIP = (riptemplate) => {
    
    if(riptemplate.interfaces!=undefined && riptemplate.interfaces!="" && !(riptemplate.interfaces instanceof Array)){
        riptemplate.interfaces = riptemplate.interfaces.split(',')
    }
    
    if(riptemplate.interfaces==undefined ||riptemplate.interfaces=="" && !(riptemplate.interfaces instanceof Array)){
        riptemplate.interfaces = []
    }

    loadInstancesRIP()
    loadInterfaces()
    selected_rip.value = riptemplate
    
}

watch(router_rip, () => {
    loadTemplatesRIP()
})

onBeforeMount(() => {
   
    loadRouters()
    loadTemplatesRIP()

})
</script>

<template>
   <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">RIP</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">RIP Templates</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'RIPTemplateCreate'}"
                                                    :title="`Create RIP Template`">
                                    <button class="btn btn-primary">Add RIP Interface-Template</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                        <select class="custom-select custom-select-lg" v-model="router_rip">
                            <option value="all" selected>All</option>
                            <option :value="router.id" v-for="router in routers">{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Name</th>
                                        <th>Instance</th>
                                        <th>Interfaces</th>
                                        <th>Mode</th>
                                        <th>Cost</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                      
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="riptemplates.length==0">
                                        <td colspan="9" class="text-center" style="height:55px!important;">There are no RIP Interface-Templates.</td>
                                    </tr>
                                    <tr v-for="riptemplate in riptemplates">
                                  
                                        <td>{{riptemplate['.id'].substring(1)}}</td>
                                        <td>#{{riptemplate.router}}</td>

                                        <td v-if="riptemplate.name==undefined">-</td>
                                        <td v-else>{{riptemplate.name}}</td>

                                        <td v-if="riptemplate.instance==undefined">-</td>
                                        <td v-else>{{riptemplate.instance}}</td>

                                        <td v-if="riptemplate.interfaces==undefined || riptemplate.interfaces==''">-</td>
                                        <td v-else>{{riptemplate.interfaces}}</td>

                                        <td v-if="riptemplate.mode==undefined">-</td>
                                        <td v-else>{{riptemplate.mode}}</td>

                                        <td v-if="riptemplate.cost==undefined">-</td>
                                        <td v-else>{{riptemplate.cost}}</td>

                                        <td class="text-success" v-if="riptemplate.disabled==undefined">false</td>
                                        <td class="text-success" v-if="riptemplate.disabled=='false'">{{riptemplate.disabled}}</td>
                                        <td class="text-danger" v-if="riptemplate.disabled=='true'">{{riptemplate.disabled}}</td>

                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteTemplatesRIP(riptemplate)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showTemplatesRIP(riptemplate)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        

                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

               <rip-template-edit :ripinstances="ripinstances" :interfaces="interfaces" :riptemplate="selected_rip" v-if="selected_rip"></rip-template-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
