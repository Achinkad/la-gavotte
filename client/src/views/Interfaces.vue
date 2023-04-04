<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useInterfaceStore } from "../stores/interface.js"
import { useRouterStore } from "../stores/router.js"

const routerStore = useRouterStore()
const interfaceStore = useInterfaceStore()

const axiosApi = inject('axiosApi')

var router_interfaces = ref("all")
var type_interfaces = ref("all")

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadInterfaces = (() => { interfaceStore.loadInterfaces(router_interfaces,type_interfaces) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })



watch(router_interfaces, () => {
    loadInterfaces()
})

watch(type_interfaces, () => {
    loadInterfaces()
})

onBeforeMount(() => {
   loadRouters()
   loadInterfaces()

})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Interfaces</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Connected Interfaces</h4>
                     
                </div>
               
                <div class="card-body pt-0">
                   
                <select class="custom-select custom-select-lg" v-model="router_interfaces">
                    <option value="all" selected>All</option>
                    <option :value="router.ip_address" v-for="router in routers">{{router.identity}}/{{router.ip_address}}</option>
                </select>
                <select class="custom-select custom-select-lg" v-model="type_interfaces">
                    <option value="all" selected>All</option>
                    <option value="ether">Ethernet</option>
                    <option value="wlan">Wireless</option>
                    <option value="bridge">Bridge</option>
                    <option value="wg">Wireguard</option>

                </select>
               
                 <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Router</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Actual MTU</th>
                                        <th>L2 MTU</th>
                                        <th>TX</th>
                                        <th>RX</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="iface in interfaces">
                                        <th>{{iface.router}}</th>
                                        <td>{{iface.name}}</td>
                                        <td>{{iface.type}}</td>
                                        <td>{{iface['actual-mtu']}}</td>
                                        <td>{{iface.l2mtu}}</td>
                                        <td>{{iface['rx-byte']}} kbps</td>
                                        <td>{{iface['tx-byte']}} kbps</td>   
                                </tr>
                            </tbody>
                </table>
               
                </div>
            </div>
        </div>
    </div>
</template>
