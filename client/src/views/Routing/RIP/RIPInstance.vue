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
import RipInstanceEdit from "./RIPInstanceEdit.vue"

var router_rip = ref("all")
var selected_rip= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadInstancesRIP = (() => { routingStore.loadInstancesRIP(router_rip) })
const ripinstances = computed(() => { return routingStore.getInstancesRIP() })


const deleteInstancesRIP = (ripinstance) => {
    
    routingStore.deleteInstancesRIP(ripinstance)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
}


const showInstancesRIP = (ripinstance) => {
    if(ripinstance.redistribute!=undefined && !(ripinstance.redistribute instanceof Array)){
        ripinstance.redistribute = ripinstance.redistribute.split(',')
    }

    selected_rip.value = ripinstance
    
}

watch(router_rip, () => {
    loadInstancesRIP()
})

onBeforeMount(() => {
   
    loadRouters()
    loadInstancesRIP()

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
                            <h4 class="header-title">RIP Instances</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'RIPInstanceCreate'}"
                                                    :title="`Create RIP Instance`">
                                    <button class="btn btn-primary">Add RIP Instance</button>
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
                                        <th>AFI</th>
                                        <th>Redistribute</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                      
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ripinstances.length==0">
                                        <td colspan="7" class="text-center" style="height:55px!important;">There are no RIP Instances.</td>
                                    </tr>
                                    <tr v-for="ripinstance in ripinstances">
                                  
                                        <td>{{ripinstance['.id'].substring(1)}}</td>
                                        <td>#{{ripinstance.router}}</td>

                                        <td v-if="ripinstance.name==undefined">-</td>
                                        <td v-else>{{ripinstance.name}}</td>

                                        <td v-if="ripinstance.afi==undefined">-</td>
                                        <td v-else>{{ripinstance.afi}}</td>

                                        <td v-if="ripinstance.redistribute==undefined || ripinstance.redistribute==''">-</td>
                                        <td v-else>{{ripinstance.redistribute}}</td>

                                        <td class="text-success" v-if="ripinstance.disabled==undefined">false</td>
                                        <td class="text-success" v-if="ripinstance.disabled=='false'">{{ripinstance.disabled}}</td>
                                        <td class="text-danger" v-if="ripinstance.disabled=='true'">{{ripinstance.disabled}}</td>

                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteInstancesRIP(ripinstance)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showInstancesRIP(ripinstance)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        

                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div>
                    </div>

               <rip-instance-edit :ripinstance="selected_rip" v-if="selected_rip"></rip-instance-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
