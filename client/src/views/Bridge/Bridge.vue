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
const router = useRouter()


var router_bridges = ref("-")
var selected_bridge= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadBridges = (() => { bridgeStore.loadBridges(router_bridges) })
const bridges = computed(() => { return bridgeStore.getBridges() })


const deleteBridge = (bridge) => {


    bridgeStore.deleteBridges(bridge)

}


const showBridge = (bridge) => {

    selected_bridge.value = bridge

}

watch(router_bridges, () => {
    loadBridges()
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
                    <select class="form-select" v-model="router_bridges">
                        <option value="-" selected hidden disabled v-if="routers.length > 0">Select a router</option>
                        <option value="-" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
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

                            <table class="table table-responsive align-middle" >
                                <thead class="table-light">

                                    <tr>
                                        <th style="width:10%;">#ID</th>
                                        <th>Name</th>
                                        <th>L2 MTU/Actual MTU</th>
                                        <th>MAC Address</th>
                                        <th>Protocol</th>
                                        <th class="text-center" style="width:10%;">Status</th>
                                        <th class="text-center" style="width:10%;">Actions</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr v-if="bridges.length==0">
                                        <td colspan="7" class="text-center" style="height:55px!important;">There are no bridges.</td>
                                    </tr>
                                    <tr v-for="bridge in bridges">

                                        <td v-if="bridge['.id']==undefined">-</td>
                                        <td v-else>{{bridge['.id'].substring(1)}}</td>

                                        <td v-if="bridge.name==undefined">-</td>
                                        <td v-else>{{bridge.name}}</td>

                                        <td v-if="bridge.l2mtu==undefined || bridge['actual-mtu']==undefined">-</td>
                                        <td v-else>{{bridge.l2mtu}}/{{bridge['actual-mtu']}}</td>

                                        <td v-if="bridge['mac-address']==undefined">-</td>
                                        <td v-else>{{bridge['mac-address']}}</td>

                                        <td v-if="bridge['protocol-mode']==undefined">-</td>
                                        <td v-else>{{bridge['protocol-mode']}}</td>

                                        <td class="text-center" v-if="bridge.disabled==undefined">
                                            <span class="badge badge-success-lighten">Active</span>
                                        </td>
                                        <td class="text-center" v-if="bridge.disabled=='false'">
                                            <span class="badge badge-success-lighten">Active</span>
                                        </td>
                                        <td class="text-center" v-if="bridge.disabled=='true'">
                                            <span class="badge badge-danger-lighten">Disabled</span>
                                        </td>
                                         <td>
                                            <div class="d-flex justify-content-center">

                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="showBridge(bridge)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                               <button class="btn btn-xs btn-light ms-2 table-button" title="Delete" @click="deleteBridge(bridge)">
                                                    <i class="bi bi-trash3"></i>
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
