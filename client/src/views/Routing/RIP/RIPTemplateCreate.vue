<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"
import { useInterfaceStore } from "../../../stores/interface.js"

const interfaceStore = useInterfaceStore()
const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const routerIdentification = ref("-")

const riptemplate = ref({
    interfaces: [],
    mode: 'passive',
    cost: '0'
})

const loadInstancesRIP = (() => { routingStore.loadInstancesRIP(routerIdentification) })
const ripinstances = computed(() => { return routingStore.getInstancesRIP() })

const loadInterfaces = (() => { interfaceStore.loadInterfaces(routerIdentification,type_all) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })


const type_all = ref("all")

const loadInstancesAndInterfaces = () => {
    loadInstancesRIP()
    loadInterfaces()
}

const createTemplateRIP = () => {

    let formData = new FormData()


    if(riptemplate.value.name!=undefined){
        formData.append('name', riptemplate.value.name)
    }

    if(riptemplate.value.instance!=undefined){
        formData.append('instance', riptemplate.value.instance)
    }


    if(riptemplate.value.interfaces!=[]){

        formData.append('interfaces', riptemplate.value.interfaces)
    }

    formData.append('mode', riptemplate.value.mode)

    formData.append('cost', riptemplate.value.cost)
    
    formData.append('identity', routerIdentification.value)

   
    if (routingStore.createTemplatesRIP(formData)) {
        notyf.success('A new RIP Template has been added.')
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
                <h2 class="p-title">Create RIP Interface-Template</h2>
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
                            <router-link :to="{ name: 'RIPTemplate'}"
                                                    :title="`RIPTemplate`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createTemplateRIP">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" @change="loadInstancesAndInterfaces()" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            <div v-if="routerIdentification!='-'" class="row">

                                <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the RIP Template name" v-model="riptemplate.name">
                                </div>

                                 <div class="col-6 mt-3">

                                <label>Instance</label>
                                <div>
                                    <select class="custom-select" v-model="riptemplate.instance">

                                        <option v-for="instance in ripinstances" :value="instance.name">{{ instance.name }}</option>
                                    </select>
                                    </div>
                                </div>

                                
                                    <div class="col-6 mt-3">
                                <label>Select Interfaces</label>
                                    <select class="form-select" v-model="riptemplate.interfaces" multiple>
                
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>

                                <label>Mode</label>
                                <div>
                                    <select class="custom-select" v-model="riptemplate.mode">

                                        <option value="passive">Passive</option>
                                        <option value="strict">Strict</option>
                                    </select>
                                </div>
                               

                                <div class="col-6 mt-3">
                                    <label for="cost" class="form-label">Cost</label>
                                    <input type="number" class="form-control" id="name" placeholder="Enter the RIP Interface-Template Cost" v-model="riptemplate.cost">
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
