<script setup>
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useInterfaceStore } from "../stores/interface.js"
import { useRouterStore } from "../stores/router.js"

const routerStore = useRouterStore()
const interfaceStore = useInterfaceStore()

const axiosApi = inject('axiosApi')

var router_interfaces = ref("-")
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

})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <select class="form-select" v-model="router_interfaces">
                        <option value="-" selected hidden disabled v-if="routers.length > 0">Select a router</option>
                        <option value="-" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">Interfaces </h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">List of all available interfaces</h4>
                    <div class="d-flex justify-content-end" v-if="router_interfaces != '-'">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="type_interfaces" class="form-label mb-0">Type:</label>
                            </div>
                            <div class="col-auto">
                                <select id="type_interfaces" class="form-select" v-model="type_interfaces">
                                    <option value="all" selected>All</option>
                                    <option value="ether">Ethernet</option>
                                    <option value="wlan">Wireless</option>
                                    <option value="bridge">Bridge</option>
                                    <option value="wg">Wireguard</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-responsive align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width:5%">#ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Actual MTU</th>
                                <th>L2 MTU</th>
                                <th>TX</th>
                                <th>RX</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="interfaces.length==0">
                                <td colspan="8" class="text-center" style="height:55px!important;">There are no interfaces.</td>
                            </tr>
                            <tr v-for="iface in interfaces">

                                <td class="text-center" v-if="iface['.id']==undefined" style="height:55px!important;"> -</td>
                                <td class="text-center" style="height:55px!important;">{{iface['.id'].substring(1)}}</td>

                                <td v-if="iface.name==undefined"> -</td>
                                <td v-else>{{iface.name}}</td>

                                <td v-if="iface.type==undefined"> -</td>
                                <td v-else>{{iface.type}}</td>

                                <td v-if="iface['actual-mtu']==undefined"> -</td>
                                <td v-else>{{iface['actual-mtu']}}</td>

                                <td v-if="iface.l2mtu==undefined"> -</td>
                                <td v-else>{{iface.l2mtu}}</td>

                                <td v-if="iface['rx-byte']==undefined"> -</td>
                                <td v-else>{{iface['rx-byte']}} kbps</td>

                                <td v-if="iface['tx-byte']==undefined"> -</td>
                                <td v-else>{{iface['tx-byte']}} kbps</td>

                                <td class="text-center" v-if="iface.disabled==undefined">
                                    <span class="badge badge-success-lighten">Active</span>
                                </td>

                                <td class="text-center" v-if="iface.disabled=='false'">
                                    <span class="badge badge-success-lighten">Active</span>
                                </td>

                                <td class="text-center" v-if="iface.disabled=='true'">
                                    <span class="badge badge-danger-lighten">Disabled</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</template>
