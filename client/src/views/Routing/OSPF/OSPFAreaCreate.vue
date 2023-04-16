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

const loadInstancesOSPF = (() => { routingStore.loadInstancesOSPF(routerIdentification) })
const ospfinstances = computed(() => { return routingStore.getInstancesOSPF() })

const routerIdentification = ref("-")

const ospfarea = ref({
    type: 'default',
    'area-id': '0.0.0.0'
})

const createAreaOSPF = () => {

    let formData = new FormData()


    if(ospfarea.value['name']!=undefined){
        formData.append('name', ospfarea.value.name)
    }

    if(ospfarea.value.instance!=undefined){
        formData.append('instance', ospfarea.value.instance)
    }

    formData.append('type', ospfarea.value.type)
    

    formData.append('area_id', ospfarea.value['area-id'])
    
    formData.append('identity', routerIdentification.value)

    routingStore.createAreasOSPF(formData)
    
    
}


onBeforeMount(() => {
   
    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create OSPF Area</h2>
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
                            <router-link :to="{ name: 'OSPFArea'}"
                                                    :title="`OSPFArea`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                       

                           <form class="row g-3" @submit.prevent="createAreaOSPF">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" @change="loadInstancesOSPF()" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            

                            <div v-if="routerIdentification!='-'" class="row">

                                <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the OSPF Area name" v-model="ospfarea.name">
                                </div>

                                <div class="col-6 mt-3">
                                <label>Select Instance</label>
                                    <select class="form-select" v-model="ospfarea.instance" required>
                                        <option value="-" selected hidden disabled>Select a Instance</option>
                                        <option v-for="instance in ospfinstances" :value="instance.name">{{ instance.name }}</option>
                                    </select>
                                </div>

                                
                                <div class="col-6 mt-3">
                                <label>Type</label>
                                <div>
                                    <select class="custom-select" v-model="ospfarea.type">

                                        <option value="default">default</option>
                                        <option value="nssa">nssa</option>
                                        <option value="stub">stub</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Area Id</label>
                                    <input type="text" class="form-control" id="router_id" placeholder="Enter the OSPF Area ID"
                                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                    v-model="ospfarea['area-id']">
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
