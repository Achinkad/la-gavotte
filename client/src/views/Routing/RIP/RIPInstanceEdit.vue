<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    ripinstance: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.ripinstance.disabled = props.ripinstance.disabled=='true' ? 'false' : 'true';

}



const editInstanceRIP = () => { 
    
    let formData = new FormData()

    formData.append('rip_identity', props.ripinstance['.id'])

    if(props.ripinstance.name!=undefined){
        formData.append('name', props.ripinstance.name)
    }

    if(props.ripinstance.afi!=undefined){
        formData.append('afi', props.ripinstance.afi)
    }

    if(props.ripinstance.redistribute!=[]){

        formData.append('redistribute', props.ripinstance.redistribute)
    }

    
    if(props.ripinstance.disabled!=undefined){
        formData.append('disabled',props.ripinstance.disabled)
    }

    formData.append('router_identity', props.ripinstance.router)

   routingStore.editInstancesRIP(formData)
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the RIP Instance:{{ripinstance['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editInstanceRIP">
         
                              <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the RIP Instance name" v-model="ripinstance.name">
                                </div>

                                 <div class="col-6 mt-3">

                                <label class="form-label">AFI</label>
                                <div>
                                    <select class="form-select" v-model="ripinstance.afi">

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


                           <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="ripinstance.disabled!=undefined && ripinstance.disabled != 'false'">
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