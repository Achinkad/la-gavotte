<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useBridgeStore } from "../../stores/bridge.js"
import { useRouterStore } from "../../stores/router.js"
import { useInterfaceStore } from "../../stores/interface.js"

const routerStore = useRouterStore()
const bridgeStore = useBridgeStore()
const interfaceStore = useInterfaceStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })
const loadBridges = (() => { bridgeStore.loadBridges(routerIdentification) })
const bridges = computed(() => { return bridgeStore.getBridges() })
const loadInterfaces = (() => { interfaceStore.loadInterfaces(routerIdentification,type_all) })
const interfaces = computed(() => { return interfaceStore.getEtherWlanIfaces() })

const routerIdentification = ref("-")


const type_all = ref("all")


const port = ref({
    interface: null,
    bridge: null,

})

const loadBridgesInterfaces = () => {
    loadInterfaces()
    loadBridges()
    
}

const createPort = () => {
    let formData = new FormData()

    formData.append('interface', port.value.interface)
    formData.append('bridge', port.value.bridge)
    formData.append('identity', routerIdentification.value)

   
    if (bridgeStore.createPorts(formData)) {
        notyf.success('A new Port has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }
}

onBeforeMount(() => {

    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create Port</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <div class="px-1">
                            <router-link :to="{ name: 'Bridge'}"
                                                    :title="`Bridge`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                           <form class="row g-3" @submit.prevent="createPort">
                           
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" @change="loadBridgesInterfaces()" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            <div v-if="routerIdentification!='-'">
                                <div class="col-6">
                                <label>Select Interface</label>
                                    <select class="form-select" v-model="port.interface" required>
                                        <option value="-" selected hidden disabled>Select a interface</option>
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :key="iface.name" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                <label>Select Bridge</label>
                                    <select class="form-select" v-model="port.bridge" required>
                                        <option value="-" selected hidden disabled>Select a bridge</option>
                                        <option v-for="bridge in bridges" :key="bridge.name" :value="bridge.name">{{ bridge.name }}</option>
                                    </select>
                                </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
