<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const routerIdentification = ref("-")

const bgpconnection = ref({
    'local.role':'ibgp'
})

const createConnectionBGP = () => {

    let formData = new FormData()

    if(bgpconnection.value['name']!=undefined){
        formData.append('name', bgpconnection.value.name)
    }

    if(bgpconnection.value['router-id']!=undefined){
        formData.append('router_id', bgpconnection.value['router-id'])
    }

    if(bgpconnection.value['remote.address']!=undefined){
        formData.append('remote_address', bgpconnection.value['remote.address'])
    }

    if(bgpconnection.value['local.address']!=undefined){
        formData.append('local_address', bgpconnection.value['local.address'])
    }
    
    if(bgpconnection.value['remote.as']!=undefined){
        formData.append('remote_as', bgpconnection.value['remote.as'])
    }
    
    if(bgpconnection.value.as!=undefined){
        formData.append('as', bgpconnection.value.as)
    }

    if(bgpconnection.value['remote.port']!=undefined){
        formData.append('remote_port', bgpconnection.value['remote.port'])
    }

    if(bgpconnection.value['local.port']!=undefined){
        formData.append('local_port', bgpconnection.value['local.port'])
    }
    
    
    formData.append('local_role', bgpconnection.value['local.role'])
    
    formData.append('identity', routerIdentification.value)

   
    routingStore.createConnectionsBGP(formData)
    
    
}


onBeforeMount(() => {
   
    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create BGP Connection</h2>
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
                            <router-link :to="{ name: 'BGP'}"
                                                    :title="`BGP`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createConnectionBGP">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            <div v-if="routerIdentification!='-'" class="row">

                                <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the BGP Connection name" v-model="bgpconnection.name">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Router ID</label>
                                    <input type="text" class="form-control" id="router_id" placeholder="Enter the Router ID"
                                    pattern="^!?\b(?:\d{1,3}\.){3}\d{1,3}\b$"
                                    v-model="bgpconnection['router-id']">
                                </div>

                                 <div class="col-6 mt-3">
                                    <label for="remote_address" class="form-label">Remote Address</label>
                                    <input type="text" class="form-control" id="remote_address" placeholder="Enter the Remote Address"
                                    pattern="^!?\b(?:\d{1,3}\.){3}\d{1,3}\b$"
                                    v-model="bgpconnection['remote.address']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="local_address" class="form-label">Local Address</label>
                                    <input type="text" class="form-control" id="local_address" placeholder="Enter the Local Address"
                                    pattern="^!?\b(?:\d{1,3}\.){3}\d{1,3}\b$"
                                    v-model="bgpconnection['local.address']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="remote_as" class="form-label">Remote AS</label>
                                    <input type="text" class="form-control" id="remote_as" placeholder="Enter the Remote AS" v-model="bgpconnection['remote.as']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="as" class="form-label">AS</label>
                                    <input type="text" class="form-control" id="as" placeholder="Enter the AS" v-model="bgpconnection.as">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="remote_port" class="form-label">Remote Port</label>
                                    <input type="text" class="form-control" id="remote_port" placeholder="Enter the Remote Port" v-model="bgpconnection['remote.port']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="local_port" class="form-label">Local Port</label>
                                    <input type="text" class="form-control" id="local_port" placeholder="Enter the Local Port" v-model="bgpconnection['local.port']">
                                </div>

                                <div class="col-6 mt-3">
                                    <label>Select Local Role</label>
                                    <select class="form-select" v-model="bgpconnection['local.role']">
                                        <option value="ebgp">ebgp</option>
                                        <option value="ebgp-peer">ebgp-peer</option>
                                        <option value="ebgp-provider">ebgp-provider</option>
                                        <option value="ebgp-rs">ebgp-rs</option>
                                        <option value="ebgp-rs-client">ebgp-rs-client</option>
                                        <option value="ibgp">ibgp</option>
                                        <option value="ibgp-rr">ibgp-rr</option>
                                        <option value="ibgp-rr-client">ibgp-rr-client</option>
                                    </select>
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
