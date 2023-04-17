<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRouterStore } from "../../stores/router.js"
import { useBridgeStore } from "../../stores/bridge.js"
import { useInterfaceStore } from "../../stores/interface.js"
import { useRouter } from 'vue-router'
import PortEdit from "./PortEdit.vue"


const bridgeStore = useBridgeStore()
const routerStore = useRouterStore()
const interfaceStore = useInterfaceStore()

const axiosApi = inject('axiosApi')
const router = useRouter()

var router_ports = ref("-")
var selected_port= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadPorts = (() => { bridgeStore.loadPorts(router_ports) })
const ports = computed(() => { return bridgeStore.getPorts() })
const loadBridges = (() => { bridgeStore.loadBridges(router_ports) })
const bridges = computed(() => { return bridgeStore.getBridges() })
const loadInterfaces = (() => { interfaceStore.loadInterfaces(router_ports,type_all) })
const interfaces = computed(() => { return interfaceStore.getEtherWlanIfaces() })

const type_all = ref("all")


const deletePort = (port) => {
    bridgeStore.deletePorts(port)

}

const showPort = (port) => {

    selected_port.value = port

    loadInterfaces()
    loadBridges()

}

const toggleDisabled = () => {

    selected_port.value.disabled = selected_port.value.disabled=='true' ? 'false' : 'true';
}



watch(router_ports, () => {

    loadPorts()
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
                    <select class="form-select" v-model="router_ports">
                        <option value="-" selected hidden disabled>Select a router</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">Ports</h2>
            </div>
        </div>
    </div>
    <div class="col-md-12">
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
            <table class="table table-responsive align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:10%;">#ID</th>
                        <th>Interface</th>
                        <th>Bridge</th>
                        <th class="text-center" style="width:15%;">Status</th>
                        <th class="text-center" style="width:15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="ports.length==0">
                        <td colspan="5" class="text-center" style="height:55px!important;">There are no ports.</td>
                    </tr>
                    <tr v-for="port in ports">

                        <td v-if="port['.id']==undefined">-</td>
                        <td v-else>{{port['.id'].substring(1)}}</td>

                        <td v-if="port.interface==undefined">-</td>
                        <td v-else>{{port.interface}}</td>

                        <td v-if="port.bridge==undefined">-</td>
                        <td v-else>{{port.bridge}}</td>

                        <td class="text-center" v-if="port.disabled==undefined">
                            <span class="badge badge-success-lighten">Active</span>
                        </td>
                        <td class="text-center" v-if="port.disabled=='false'">
                            <span class="badge badge-success-lighten">Active</span>
                        </td>
                        <td class="text-center" v-if="port.disabled=='true'">
                            <span class="badge badge-danger-lighten">Disabled</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">

                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="showPort(port)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-xs btn-light ms-2 table-button" title="Delete" @click="deletePort(port)">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <port-edit :port="selected_port" :bridges="bridges" :interfaces="interfaces" v-if="selected_port"></port-edit>

</div>


</template>
