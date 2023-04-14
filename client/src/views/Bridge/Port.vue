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
const notyf = inject('notyf')
const router = useRouter()

var router_ports = ref("all")
var selected_port= ref(null)

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadPorts = (() => { bridgeStore.loadPorts(router_ports) })
const ports = computed(() => { return bridgeStore.getPorts() })
const loadBridges = ((router_identifier) => { bridgeStore.loadBridges(router_identifier) })
const bridges = computed(() => { return bridgeStore.getBridges() })
const loadInterfaces = ((router_identifier) => { interfaceStore.loadInterfaces(router_identifier,type_all) })
const interfaces = computed(() => { return interfaceStore.getEtherWlanIfaces() })

const type_all = ref("all")


const deletePort = (port) => {
    bridgeStore.deletePorts(port)

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
  
}

const showPort = (port) => {

    selected_port.value = port

    loadInterfaces(ref(selected_port.value.router))
    loadBridges(ref(selected_port.value.router))

    /*
    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new Bridge has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }*/
    
}

const toggleDisabled = () => {

    selected_port.value.disabled = selected_port.value.disabled=='true' ? 'false' : 'true';
}



watch(router_ports, () => {
 
   loadPorts()
})

onBeforeMount(() => {

    loadRouters()
    loadPorts()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
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
                <select class="custom-select custom-select-lg" v-model="router_ports">
                    <option value="all" selected>All</option>
                    <option :value="router.id" v-for="router in routers">{{router.ip_address}}</option>
                </select>
                    <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>#ID</th>
                                        <th>Router</th>
                                        <th>Interface</th>
                                        <th>Bridge</th>
                                        <th>Disabled</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr v-if="ports.length==0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">There are no ports.</td>
                                    </tr>
                                    <tr v-for="port in ports">
                                        <td>{{port['.id'].substring(1)}}</td>
                                        <td>#{{port.router}}</td>
                                        <td>{{port.interface}}</td>
                                        <td>{{port.bridge}}</td> 
                                
                                        <td class="text-success" v-if="port.disabled=='false'">{{port.disabled}}</td>
                                        <td class="text-danger" v-if="port.disabled=='true'">{{port.disabled}}</td>
                                         <td>
                                            <div class="d-flex justify-content-center">
                                               
                                                <button class="btn btn-xs btn-light" title="Delete" @click="deletePort(port)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                 <button class="btn btn-xs btn-light" title="Edit" @click="showPort(port)">
                                                    <i class="bi bi-pencil"></i>
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
