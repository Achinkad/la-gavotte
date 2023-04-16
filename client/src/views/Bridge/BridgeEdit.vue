<script setup>
import { ref, watch, computed, inject } from "vue";
import { useBridgeStore } from "../../stores/bridge.js"

const bridgeStore = useBridgeStore()

const props = defineProps({
    bridge: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.bridge.disabled = props.bridge.disabled=='true' ? 'false' : 'true';
}


const editBridge = () => { 
    
    let formData = new FormData()

    formData.append('name', props.bridge.name)
    formData.append('mtu', props.bridge.mtu)
    formData.append('protocol', props.bridge['protocol-mode'])
    formData.append('disabled', props.bridge.disabled)
    formData.append('router_identity', props.bridge.router)
    formData.append('bridge_identity', props.bridge['.id'])

    bridgeStore.editBridges(formData)
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the bridge:{{bridge['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editBridge">
        
                               <div class="col-6">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter name" v-model="bridge.name">
                                </div>
                                <div class="col-6">
                                    <label for="exampleInputEmail1">MTU [68-65535]</label>
                                    <input type="number" class="form-control" aria-describedby="emailHelp" placeholder="Enter mtu" 
                                    pattern="^([6][8-9]|[7-9]\d{1}|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="bridge.mtu">
                                </div>

                                 <div class="col-6">
                                    <p>Select the protocol</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="none" v-model="bridge['protocol-mode']">
                                        <label class="form-check-label" for="none">None</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="stp" v-model="bridge['protocol-mode']">
                                        <label class="form-check-label" for="stp">STP</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="rstp" v-model="bridge['protocol-mode']">
                                        <label class="form-check-label" for="rstp">RSTP</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="mstp" v-model="bridge['protocol-mode']">
                                        <label class="form-check-label" for="mstp">MSTP</label><br>
                                    </div>
                                </div>

                                <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="bridge.disabled == 'true'">
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