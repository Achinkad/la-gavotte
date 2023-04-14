<script setup>
import { ref, watch, computed, inject } from "vue";
import { useBridgeStore } from "../../stores/bridge.js"
const notyf = inject('notyf')
const bridgeStore = useBridgeStore()

const props = defineProps({
    port: {
        type: Object,
        required: true,
    },
    bridges: {
        type: Object,
        required: true,
    },
    interfaces: {
        type: Object,
        required: true,
    }
    
})





const editBridge = () => { 
    
   let formData = new FormData()

    formData.append('interface', port.interface)
    formData.append('bridge', port.bridge)
    formData.append('disabled', port.disabled)
    formData.append('router_identity', port.router)
    formData.append('port_identity', port['.id'])

    if (bridgeStore.editPorts(formData)) {
        notyf.success('The port has been edited.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }
    
    
}

const toggleDisabled = () => {

    props.port.disabled = props.port.disabled=='true' ? 'false' : 'true';
}


</script>

<template>
 <div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the port: {{port['.id']}} [ID]</h4>
                    
                </div>
 <div class="card-body pt-0">      
                           <form class="row g-3" @submit.prevent="editPort">
        
                               <div class="col-6">
                                <label>Select Interface</label>
                                    <select class="form-select" v-model="port.interface" required>
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                <label>Select Bridge</label>
                                    <select class="form-select" v-model="port.bridge" required>
                                        <option v-for="bridge in bridges" :value="bridge.name">{{ bridge.name }}</option>
                                    </select>
                                </div>

                                <div class="form-check form-switch col-12">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="port.disabled == 'true'">
                                    <label class="form-check-label" for="flexSwitchCheckDefault"> Disabled</label>
                                    
                                </div>


                                <button type="submit" class="btn btn-primary">Submit</button>
                               
                            </form>
                     </div>
                       </div>
</template>