<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    ospfinstance: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.ospfinstance.disabled = props.ospfinstance.disabled=='true' ? 'false' : 'true';

}



const editInstanceOSPF = () => { 
    
    let formData = new FormData()

    formData.append('ospf_identity', props.ospfinstance['.id'])

    if(props.ospfinstance['name']!=undefined){
        formData.append('name', props.ospfinstance.name)
    }

    if(props.ospfinstance['router-id']!=undefined){
        formData.append('router_id', props.ospfinstance['router-id'])
    }

    if(props.ospfinstance.redistribute!=[]){

        formData.append('redistribute', props.ospfinstance.redistribute)
    }

    formData.append('version', props.ospfinstance.version)
    
    if(props.ospfinstance.disabled!=undefined){
        formData.append('disabled',props.ospfinstance.disabled)
    }

    formData.append('router_identity', props.ospfinstance.router)

    routingStore.editInstancesOSPF(formData)
     
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the OSPF Instance:{{ospfinstance['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editInstanceOSPF">
         
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

                           <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="ospfinstance.disabled!=undefined && ospfinstance.disabled != 'false'">
                                    <label class="form-check-label" for="flexSwitchCheckDefault"> Disabled</label>
                                    
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
</template>