<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRouterStore } from "../../stores/router.js"
import { useBridgeStore } from "../../stores/bridge.js"


const bridgeStore = useBridgeStore()

const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')

var router_bridges = ref("all")
var router_ports = ref("all")

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadBridges = (() => { bridgeStore.loadBridges(router_bridges) })
const loadPorts = (() => { bridgeStore.loadPorts(router_ports) })
const bridges = computed(() => { return bridgeStore.getBridges() })
const ports = computed(() => { return bridgeStore.getPorts() })

watch(router_bridges, () => {
    loadBridges()
})

watch(router_ports, () => {
   loadPorts()
})

onBeforeMount(() => {
   
    loadRouters()
    loadBridges()
    loadPorts()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Bridge</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
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
                            <option :value="router.ip_address" v-for="router in routers">{{router.identity}}/{{router.ip_address}}</option>
                        </select>
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Name</th>
                                        <th>L2 MTU</th>
                                        <th>MAC Address</th>
                                        <th>Protocol</th>
                                        <th>Status</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="bridges.length==0">
                                        <td colspan="7" class="text-center" style="height:55px!important;">There are no bridges.</td>
                                    </tr>
                                    <tr v-for="bridge in bridges">
                                   
                                        <td>{{bridge['.id'].substring(1)}}</td>
                                        <td>{{bridge.name}}</td>
                                        <td>{{bridge.l2mtu}}</td>
                                        <td>{{bridge['mac-address']}}</td>
                                        <td>{{bridge['protocol-mode']}}</td>
                                        <td class="text-success" v-if="bridge.disabled=='false'">Active</td>
                                        <td class="text-danger" v-if="bridge.disabled=='true'">Disabled</td>
                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                             
                                                     <button class="btn btn-xs btn-light" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Connected Ports</h4>
                    <div class="px-1">
                    <router-link :to="{ name: 'PortCreate'}"
                                                    :title="`Create Port`">
                        <button type="submit" class="btn btn-primary">Add Port</button>
                        </router-link>
                    </div>
                </div>
                <div class="card-body pt-0">
                <select class="custom-select custom-select-lg" v-model="router_ports">
                    <option value="all" selected>All</option>
                    <option :value="router.ip_address" v-for="router in routers">{{router.identity}}/{{router.ip_address}}</option>
                </select>
                    <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Interface</th>
                                        <th>Bridge</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ports.length==0">
                                        <td colspan="4" class="text-center" style="height:55px!important;">There are no ports.</td>
                                    </tr>
                                    <tr v-for="port in ports">
                                        <td>{{port['.id'].substring(1)}}</td>
                                        <td>{{port.interface}}</td>
                                        <td>{{port.bridge}}</td>
                                        <td class="text-success" v-if="port.disabled=='false'">Active</td>
                                        <td class="text-danger" v-if="port.disabled=='true'">Disabled</td>
                                    </tr>
                                </tbody>
                            </table>
                </div>
            </div>
        </div>
    </div>
</template>
