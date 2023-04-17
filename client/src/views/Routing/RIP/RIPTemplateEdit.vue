<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    riptemplate: {
        type: Object,
        required: true,
    },
    ripinstances: {
        type: Object,
        required: true,
    },
    interfaces: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.riptemplate.disabled = props.riptemplate.disabled=='true' ? 'false' : 'true';

}



const editTemplateRIP = () => { 
    
    let formData = new FormData()

    formData.append('rip_identity', props.riptemplate['.id'])

    if(props.riptemplate.name!=undefined){
        formData.append('name', props.riptemplate.name)
    }

    if(props.riptemplate.instance!=undefined){
        formData.append('instance', props.riptemplate.instance)
    }

    if(props.riptemplate.interfaces!=[]){

        formData.append('interfaces', props.riptemplate.interfaces)
    }

    if(props.riptemplate.mode!=undefined){
        formData.append('mode', props.riptemplate.mode)
    }

    if(props.riptemplate.cost!=undefined){
        formData.append('cost', props.riptemplate.cost)
    }
    
    if(props.riptemplate.disabled!=undefined){
        formData.append('disabled',props.riptemplate.disabled)
    }

    formData.append('router_identity', props.riptemplate.router)

    routingStore.editTemplatesRIP(formData)
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the RIP Interface-Template:{{riptemplate['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editTemplateRIP">
         
                            <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the RIP Template name" v-model="riptemplate.name">
                                </div>

                                 <div class="col-6 mt-3">

                                <label>Instance</label>
                                <div>
                                    <select class="custom-select" v-model="riptemplate.instance">

                                        <option v-for="instance in ripinstances" :value="instance.name">{{ instance.name }}</option>
                                    </select>
                                    </div>
                                </div>

                                
                                    <div class="col-6 mt-3">
                                <label>Select Interfaces</label>
                                    <select class="form-select" v-model="riptemplate.interfaces" multiple>
                
                                        <option value="all">All</option>
                                        <option value="dynamic">Dynamic</option>
                                        <option value="none">None</option>
                                        <option value="static">Static</option>
                                        <option v-for="iface in interfaces" :value="iface.name">{{ iface.name }}</option>
                                    </select>
                                </div>

                                <label>Mode</label>
                                <div>
                                    <select class="custom-select" v-model="riptemplate.mode">

                                        <option value="passive">Passive</option>
                                        <option value="strict">Strict</option>
                                    </select>
                                </div>
                               

                                <div class="col-6 mt-3">
                                    <label for="cost" class="form-label">Cost</label>
                                    <input type="number" class="form-control" id="name" placeholder="Enter the RIP Interface-Template Cost" v-model="riptemplate.cost">
                                </div>


                           <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="riptemplate.disabled!=undefined && riptemplate.disabled != 'false'">
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