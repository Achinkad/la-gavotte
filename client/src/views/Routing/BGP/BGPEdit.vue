<script setup>
import { ref, watch, computed, inject } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    bgpconnection: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.bgpconnection.disabled = props.bgpconnection.disabled=='true' ? 'false' : 'true';

}



const editConnectionBGP = () => { 
    
    let formData = new FormData()

    formData.append('bgp_identity', props.bgpconnection['.id'])

   if(props.bgpconnection.name!=undefined){
        formData.append('name', props.bgpconnection.name)
    }

    if(props.bgpconnection['router-id']!=undefined){
        formData.append('router_id', props.bgpconnection['router-id'])
    }

    if(props.bgpconnection['remote.address']!=undefined){
        formData.append('remote_address', props.bgpconnection['remote.address'])
    }

    if(props.bgpconnection['local.address']!=undefined){
        formData.append('local_address', props.bgpconnection['local.address'])
    }
    
    if(props.bgpconnection['remote.as']!=undefined){
        formData.append('remote_as', props.bgpconnection['remote.as'])
    }
    
    if(props.bgpconnection.as!=undefined){
        formData.append('as', props.bgpconnection.as)
    }

    if(props.bgpconnection['remote.port']!=undefined){
        formData.append('remote_port', props.bgpconnection['remote.port'])
    }

    if(props.bgpconnection['local.port']!=undefined){
        formData.append('local_port', props.bgpconnection['local.port'])
    }
    
    
    formData.append('local_role', props.bgpconnection['local.role'])

    if(props.bgpconnection.disabled!=undefined){
        formData.append('disabled',props.bgpconnection.disabled)
    }

    formData.append('router_identity', props.bgpconnection.router)

   
    if (routingStore.editConnectionsBGP(formData)) {
        notyf.success('A bgpconnection has been edited.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }
   
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the bridge:{{bgpconnection['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editConnectionBGP">
         
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

                           <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="bgpconnection.disabled!=undefined && bgpconnection.disabled != 'false'">
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