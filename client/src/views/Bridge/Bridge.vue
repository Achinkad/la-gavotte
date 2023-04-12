<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRouterStore } from "../../stores/router.js"
import { useBridgeStore } from "../../stores/bridge.js"
import { useRouter } from 'vue-router'
import BridgeEdit from "./BridgeEdit.vue"


const bridgeStore = useBridgeStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')
const router = useRouter()


var router_bridges = ref("all")
var selected_bridge= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadBridges = (() => { bridgeStore.loadBridges(router_bridges) })
const bridges = computed(() => { return bridgeStore.getBridges() })


const deleteBridge = (bridge) => {
    

    bridgeStore.deleteBridges(bridge)
    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
}


const showBridge = (bridge) => {

    selected_bridge.value = bridge
    console.log(selected_bridge.value)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
    
}

watch(router_bridges, () => {
    loadBridges()
})


onBeforeMount(() => {
   
    loadRouters()
    loadBridges()

})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Bridges</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Connected Bridges</h4>
                            <div class="px-1">
                               <router-link :to="{ name: 'BridgeCreate'}"
                                                    :title="`Create Bridge`">
                                    <button class="btn btn-primary">Add Bridge</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                        <select class="custom-select custom-select-lg" v-model="router_bridges">
                            <option value="all" selected>All</option>
                            <option :value="router.id" v-for="router in routers">{{router.identity}}/{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">
                                
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Name</th>
                                        <th>L2 MTU/Actual MTU</th>
                                        <th>MAC Address</th>
                                        <th>Protocol</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                    </tr>
                               
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="bridges.length==0">
                                        <td colspan="8" class="text-center" style="height:55px!important;">There are no bridges.</td>
                                    </tr>
                                    <tr v-for="bridge in bridges">
                                  
                                        <td>{{bridge['.id'].substring(1)}}</td>
                                        <td>#{{bridge.router}}</td>
                                        <td>{{bridge.name}}</td>
                                        <td>{{bridge.l2mtu}}/{{bridge['actual-mtu']}}</td>
                                        <td>{{bridge['mac-address']}}</td>
                                        <td>{{bridge['protocol-mode']}}</td>
                                        <td class="text-success" v-if="bridge.disabled=='false'">{{bridge.disabled}}</td>
                                        <td class="text-danger" v-if="bridge.disabled=='true'">{{bridge.disabled}}</td>
                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                               <button class="btn btn-xs btn-light" title="Delete" @click="deleteBridge(bridge)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light" title="Edit" @click="showBridge(bridge)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               
                                            </div>
                                           
                                        </td>
                                        
                                   
                                    </tr>
                                </tbody>
                            </table> 
                        </div >
                    </div>

               
                    <bridge-edit :bridge="selected_bridge" v-if="selected_bridge"></bridge-edit>
             
        </div>
     </div>
        </div>
        </div>
</template>
