<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    ospftemplate: {
        type: Object,
        required: true,
    },
    ospfareas: {
        type: Object,
        required: true,
    },
    interfaces: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.ospftemplate.disabled = props.ospftemplate.disabled=='true' ? 'false' : 'true';

}



const editTemplateOSPF = () => { 
    
    let formData = new FormData()

    formData.append('ospf_identity', props.ospftemplate['.id'])

    if(props.ospftemplate.interfaces!=[]){
        formData.append('interfaces', props.ospftemplate.interfaces)
    }

    if(props.ospftemplate.area!=undefined){
        formData.append('area', props.ospftemplate.area)
    }

    if(props.ospftemplate.networks!=undefined){
        formData.append('networks', props.ospftemplate.networks)
    }

    formData.append('type', props.ospftemplate.type)
    
    formData.append('cost', props.ospftemplate.cost)

    if(props.ospftemplate.disabled!=undefined){
        formData.append('disabled',props.ospftemplate.disabled)
    }

    formData.append('router_identity', props.ospftemplate.router)

    routingStore.editTemplatesOSPF(formData)
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the OSPF Interface-Template:{{ospftemplate['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editTemplateOSPF">
         
                             <div class="col-6 mt-3">
                                <label>Select Interfaces</label>
                                    <select class="form-select" v-model="ospftemplate.interfaces" multiple>
                
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>

                                <div class="col-6 mt-3">
                                <label>Select Area</label>
                                    <select class="form-select" v-model="ospftemplate.area" required>
                                        
                                        <option v-for="area in ospfareas" :value="area.name">{{ area.name }}</option>
                                    </select>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="src_address" class="form-label">Networks</label>
                                    <input type="text" class="form-control" id="router_id" placeholder="Enter the OSPF Template Networks"
                                    pattern="^(\d{1,3}\.){3}\d{1,3}(,\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})*$"
                                    v-model="ospftemplate.networks">
                                </div>

                                
                                <div class="col-6 mt-3">
                                <label class="form-label">Network Type</label>
                                <div>
                                    <select class="form-select" v-model="ospftemplate.type">

                                        <option value="broadcast">broadcast</option>
                                        <option value="nbma">nbma</option>
                                        <option value="ptmp">ptmp</option>
                                        <option value="ptmp-broadcast">ptmp-broadcast</option>
                                        <option value="ptp">ptp</option>
                                        <option value="ptp-unnumbered">ptp-unnumbered</option>
                                        <option value="virtual-link">virtual-link</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="cost" class="form-label">Cost [1-65535]</label>
                                    <input type="number" class="form-control" id="name" placeholder="Enter the OSPF Interface-Template Cost" 
                                    pattern="^([1-9]|[1-9][0-9]|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="ospftemplate.cost">
                                </div>

                                <div class="col-6 mt-3">
                                    <label for="priority" class="form-label">Priority [0-255]</label>
                                    <input type="number" class="form-control" id="name" placeholder="Enter the OSPF Interface-Template Priority"
                                    pattern="^(0|[1-9][0-9]?|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
                                    v-model="ospftemplate.priority">
                                </div>

                                <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="ospftemplate.disabled!=undefined && ospftemplate.disabled != 'false'">
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