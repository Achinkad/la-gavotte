<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useRoutingStore } from "../../../stores/routing.js"
import { useRouterStore } from "../../../stores/router.js"

const routingStore = useRoutingStore()
const routerStore = useRouterStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')


const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const routerIdentification = ref("-")

const ospfinstance = ref({
    redistribute: [],
    version: '2'
})

const createInstanceOSPF = () => {

    let formData = new FormData()


    if(ospfinstance.value['name']!=undefined){
        formData.append('name', ospfinstance.value.name)
    }

    if(ospfinstance.value['router-id']!=undefined){
        formData.append('router_id', ospfinstance.value['router-id'])
    }


    if(ospfinstance.value.redistribute!=[]){

        formData.append('redistribute', ospfinstance.value.redistribute)
    }


    formData.append('version', ospfinstance.value.version)
    
    formData.append('identity', routerIdentification.value)

   
    routingStore.createInstancesOSPF(formData)
    
}


onBeforeMount(() => {
   
    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create OSPF Instance</h2>
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
                            <router-link :to="{ name: 'OSPFInstance'}"
                                                    :title="`OSPFInstance`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createInstanceOSPF">
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
                                    <input type="text" class="form-control" id="name" placeholder="Enter the OSPF Instance name" v-model="ospfinstance.name">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Router ID</label>
                                    <input type="text" class="form-control" id="router_id" placeholder="Enter the OSPF Router ID"
                                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                    v-model="ospfinstance['router-id']">
                                </div>

                                 <div class="col-6 mt-3">
                                    <label for="remote_address" class="form-label">Redistribute</label>
                                    <div class="custom-control custom-checkbox">
                                    <div class="d-inline">

                                        <input type="checkbox" class="custom-control-input" value="connected" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">connected</label>
                                    </div>
                                    <div class="d-inline ms-2">

                                        <input type="checkbox" class="custom-control-input" value="static" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">static</label>
                                    </div >
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="rip" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">rip</label>
                                    </div >
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="ospf" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">ospf</label>
                                    </div>
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="bgp" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">bgp</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="vpn" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">vpn</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="dhcp" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">dhcp</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="fantasy" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">fantasy</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="modem" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">modem</label>
                                    </div>
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="copy" v-model="ospfinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">copy</label>
                                    </div>
                                    </div>
                                </div>

                                
                                <div class="col-6 mt-3">
                                <label>Version</label>
                                <div>
                                    <select class="custom-select" v-model="ospfinstance.version">

                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                    </div>
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
