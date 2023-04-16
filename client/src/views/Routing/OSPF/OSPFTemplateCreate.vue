<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useInterfaceStore } from "../../../stores/interface.js"
import { useRouterStore } from "../../../stores/router.js"

const routingStore = useRoutingStore()
const interfaceStore = useInterfaceStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const loadAreasOSPF = (() => { routingStore.loadAreasOSPF(routerIdentification) })
const ospfareas = computed(() => { return routingStore.getAreasOSPF() })

const loadInterfaces = (() => { interfaceStore.loadInterfaces(routerIdentification,type_all) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })


const type_all = ref("all")

const loadAreasAndInterfaces = () => {
    loadAreasOSPF()
    loadInterfaces()
}



const routerIdentification = ref("-")

const ospftemplate = ref({
    type: 'broadcast',
    cost: '1',
    priority: '128',
    interfaces: []
})

const createTemplateOSPF = () => {

    let formData = new FormData()


    if(ospftemplate.value.interfaces!=[]){
        formData.append('interfaces', ospftemplate.value.interfaces)
    }

    if(ospftemplate.value.area!=undefined){
        formData.append('area', ospftemplate.value.area)
    }

    if(ospftemplate.value.networks!=undefined){
        formData.append('networks', ospftemplate.value.networks)
    }

    formData.append('type', ospftemplate.value.type)
    
    formData.append('cost', ospftemplate.value.cost)

    formData.append('priority', ospftemplate.value.priority)
    
    formData.append('identity', routerIdentification.value)

    routingStore.createTemplatesOSPF(formData)
    
}


onBeforeMount(() => {
   
    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create OSPF Interface-Template</h2>
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
                            <router-link :to="{ name: 'OSPFTemplate'}"
                                                    :title="`OSPFTemplate`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createTemplateOSPF">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" @change="loadAreasAndInterfaces()" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            <div v-if="routerIdentification!='-'" class="row">

                                <div class="col-6 mt-3">
                                <label>Select Interfaces</label>
                                    <select class="form-select" v-model="ospftemplate.interfaces" multiple>
                
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>

                                <div class="col-6 mt-3">
                                <label>Select Area</label>
                                    <select class="form-select" v-model="ospftemplate.area" required>
                                        
                                        <option v-for="area in ospfareas" :value="area.name">{{ area.name }}</option>
                                    </select>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Networks</label>
                                    <input type="text" class="form-control" id="router_id" placeholder="Enter the OSPF Template Networks"
                                    pattern="^(\d{1,3}\.){3}\d{1,3}(,\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})*$"
                                    v-model="ospftemplate.networks">
                                </div>

                                
                                <div class="col-6 mt-3">
                                <label>Network Type</label>
                                <div>
                                    <select class="custom-select" v-model="ospftemplate.type">

                                        <option value="broadcast">broadcast</option>
                                        <option value="nbma">nbma</option>
                                        <option value="ptmp">ptmp</option>
                                        <option value="ptmp-broadcast">ptmp-broadcast</option>
                                        <option value="ptp">ptp</option>
                                        <option value="ptp-unnumbered">ptp-unnumbered</option>
                                        <option value="virtual-link">virtual-link</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="cost" class="form-label">Cost [1-65535]</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the OSPF Interface-Template Cost" 
                                    pattern="^([1-9]|[1-9][0-9]|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="ospftemplate.cost">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="priority" class="form-label">Priority [0-255]</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the OSPF Interface-Template Priority" 
                                    pattern="^(0|[1-9][0-9]?|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
                                    v-model="ospftemplate.priority">
                                </div>
                           
                                </div>
                            <div class="col-12 mt-4 d-flex justify-content-end">
                            <div class="px-1">
                                <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                            </div>
                            <div class="px-1">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
