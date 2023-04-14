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

var router_ospf = ref("all")
var selected_ospf= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadAreasOSPF = (() => { routingStore.loadAreasOSPF(router_ospf) })
const ospfareas = computed(() => { return routingStore.getAreasOSPF() })

const loadInstancesOSPF = (() => { routingStore.loadInstancesOSPF(router_ospf) })
const ospfinstances = computed(() => { return routingStore.getInstancesOSPF() })

const deleteAreasOSPF = (ospfarea) => {
    
    routingStore.deleteAreasOSPF(ospfarea)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
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
    loadAreasOSPF()

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
                            <h4 class="header-title">OSPF Areas</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'OSPFAreaCreate'}"
                                                    :title="`Create OSPF Area`">
                                    <button class="btn btn-primary">Add OSPF Area</button>
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
                                        <th>Name</th>
                                        <th>Instance</th>
                                        <th>Type</th>
                                        <th>Area Id</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                      
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ospfareas.length==0">
                                        <td colspan="8" class="text-center" style="height:55px!important;">There are no OSPF Areas.</td>
                                    </tr>
                                    <tr v-for="ospfarea in ospfareas">
                                  
                                        <td>{{ospfarea['.id'].substring(1)}}</td>
                                        <td>#{{ospfarea.router}}</td>

                                        <td v-if="ospfarea.name==undefined">-</td>
                                        <td v-else>{{ospfarea.name}}</td>

                                        <td v-if="ospfarea.instance==undefined">-</td>
                                        <td v-else>{{ospfarea.instance}}</td>

                                        <td v-if="ospfarea.type==undefined">-</td>
                                        <td v-else>{{ospfarea.type}}</td>

                                        <td v-if="ospfarea['area-id']==undefined">-</td>
                                        <td v-else>{{ospfarea['area-id']}}</td>

                                        <td class="text-success" v-if="ospfarea.disabled==undefined">false</td>
                                        <td class="text-success" v-if="ospfarea.disabled=='false'">{{ospfarea.disabled}}</td>
                                        <td class="text-danger" v-if="ospfarea.disabled=='true'">{{ospfarea.disabled}}</td>

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
