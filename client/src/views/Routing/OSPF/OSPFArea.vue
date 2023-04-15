<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useRouter } from 'vue-router'

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()
import OspfAreaEdit from "./OSPFAreaEdit.vue"

var router_ospf = ref("-")
var selected_ospf= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadAreasOSPF = (() => { routingStore.loadAreasOSPF(router_ospf) })
const ospfareas = computed(() => { return routingStore.getAreasOSPF() })

const loadInstancesOSPF = (() => { routingStore.loadInstancesOSPF(router_ospf) })
const ospfinstances = computed(() => { return routingStore.getInstancesOSPF() })

const deleteAreasOSPF = (ospfarea) => {
    
    routingStore.deleteAreasOSPF(ospfarea)
}


const showAreasOSPF = (ospfarea) => {
   
    loadInstancesOSPF()
    selected_ospf.value = ospfarea
    
}

watch(router_ospf, () => {
    loadAreasOSPF()
})

onBeforeMount(() => {
   
    loadRouters()

})
</script>

<template>
   <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <select class="form-select" v-model="router_ospf">
                        <option value="-" selected hidden disabled v-if="routers.length > 0">Select a router</option>
                        <option value="-" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
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
                            <h4 class="header-title">OSPF Areas</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'OSPFAreaCreate'}"
                                                    :title="`Create OSPF Area`">
                                    <button class="btn btn-primary">Add OSPF Area</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Name</th>
                                        <th>Instance</th>
                                        <th>Type</th>
                                        <th>Area Id</th>
                                        <th>About</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                      
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ospfareas.length==0">
                                        <td colspan="8" class="text-center" style="height:55px!important;">There are no OSPF Areas.</td>
                                    </tr>
                                    <tr v-for="ospfarea in ospfareas">

                                        <td v-if="ospfarea['.id']==undefined">-</td>
                                        <td v-else>{{ospfarea['.id'].substring(1)}}</td>
                                
                                        <td v-if="ospfarea.name==undefined">-</td>
                                        <td v-else>{{ospfarea.name}}</td>

                                        <td v-if="ospfarea.instance==undefined">-</td>
                                        <td v-else>{{ospfarea.instance}}</td>

                                        <td v-if="ospfarea.type==undefined">-</td>
                                        <td v-else>{{ospfarea.type}}</td>

                                        <td v-if="ospfarea['area-id']==undefined">-</td>
                                        <td v-else>{{ospfarea['area-id']}}</td>

                                        <td class="text-success" v-if="ospfarea['.about']==undefined">Everything OK</td>
                                        <td class="text-danger" v-else>{{ospfarea['.about']}}</td>

                                        <td class="text-success" v-if="ospfarea.disabled==undefined">ACTIVE</td>
                                        <td class="text-success" v-if="ospfarea.disabled=='false'">ACTIVE</td>
                                        <td class="text-danger" v-if="ospfarea.disabled=='true'">DISABLED</td>

                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteAreasOSPF(ospfarea)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showAreasOSPF(ospfarea)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        

                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

               <ospf-area-edit :ospfarea="selected_ospf" :ospfinstances="ospfinstances" v-if="selected_ospf"></ospf-area-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
