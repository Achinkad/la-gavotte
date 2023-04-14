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

const ripinstance = ref({
    redistribute: []
})

const createInstanceRIP = () => {

    let formData = new FormData()


    if(ripinstance.value.name!=undefined){
        formData.append('name', ripinstance.value.name)
    }

    if(ripinstance.value.afi!=undefined){
        formData.append('afi', ripinstance.value.afi)
    }


    if(ripinstance.value.redistribute!=[]){

        formData.append('redistribute', ripinstance.value.redistribute)
    }

    
    formData.append('identity', routerIdentification.value)

   
    if (routingStore.createInstancesRIP(formData)) {
        notyf.success('A new RIP Instance has been added.')
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
                <h2 class="p-title">Create RIP Instance</h2>
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
                            <router-link :to="{ name: 'RIPInstance'}"
                                                    :title="`RIPInstance`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createInstanceRIP">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            <div v-if="routerIdentification!='-'" class="row">

                                <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the RIP Instance name" v-model="ripinstance.name">
                                </div>

                                 <div class="col-6 mt-3">

                                <label>AFI</label>
                                <div>
                                    <select class="custom-select" v-model="ripinstance.afi">

                                        <option value="ipv4">Ipv4</option>
                                        <option value="ipv6">Ipv6</option>
                                    </select>
                                    </div>
                                </div>

                                 <div class="col-6 mt-3">
                                    <label for="remote_address" class="form-label">Redistribute</label>
                                    <div class="custom-control custom-checkbox">
                                    <div class="d-inline">

                                        <input type="checkbox" class="custom-control-input" value="connected" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">connected</label>
                                    </div>
                                    <div class="d-inline ms-2">

                                        <input type="checkbox" class="custom-control-input" value="static" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">static</label>
                                    </div >
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="rip" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">rip</label>
                                    </div >
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="ospf" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">ospf</label>
                                    </div>
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="bgp" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">bgp</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="vpn" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">vpn</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="dhcp" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">dhcp</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="fantasy" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">fantasy</label>
                                    </div>

                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="modem" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">modem</label>
                                    </div>
                                    <div class="d-inline ms-2">
                                    
                                        <input type="checkbox" class="custom-control-input" value="copy" v-model="ripinstance.redistribute">
                                        <label class="custom-control-label" for="customCheck1">copy</label>
                                    </div>
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
