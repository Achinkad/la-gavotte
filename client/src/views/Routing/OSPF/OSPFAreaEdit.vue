<script setup>
import { ref, watch, computed, inject,onBeforeMount } from "vue";
import { useRoutingStore } from "../../../stores/routing.js"
const notyf = inject('notyf')

const routingStore = useRoutingStore()

const props = defineProps({
    ospfarea: {
        type: Object,
        required: true,
    },
    ospfinstances: {
        type: Object,
        required: true,
    }
})


const toggleDisabled = () => {

    props.ospfarea.disabled = props.ospfarea.disabled=='true' ? 'false' : 'true';

}



const editInstanceOSPF = () => {
    
    let formData = new FormData()

    formData.append('ospf_identity', props.ospfarea['.id'])


    if(props.ospfarea['name']!=undefined){
        formData.append('name', props.ospfarea.name)
    }

    if(props.ospfarea.instance!=undefined){
        formData.append('instance', props.ospfarea.instance)
    }

    formData.append('type', props.ospfarea.type)
    

    formData.append('area_id', props.ospfarea['area-id'])
    
    if(props.ospfarea.disabled!=undefined){
        formData.append('disabled',props.ospfarea.disabled)
    }

    formData.append('router_identity', props.ospfarea.router)

    routingStore.editAreasOSPF(formData)
    
    
}


</script>

<template>
<div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">You're viewing the OSPF Area:{{ospfarea['.id']}} [ID]</h4>
                    
                </div>
                <div class="card-body pt-0">   
     <form class="row g-3" @submit.prevent="editInstanceOSPF">
         
                              <div class="col-6 mt-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter the OSPF Area name" v-model="ospfarea.name">
                                </div>

                                <div class="col-6 mt-3">
                                <label class="form-label">Select Instance</label>
                                    <select class="form-select" v-model="ospfarea.instance" required>
                                        <option value="-" selected hidden disabled>Select a bridge</option>
                                        <option v-for="instance in ospfinstances" :value="instance.name">{{ instance.name }}</option>
                                    </select>
                                </div>

                                
                                <div class="col-6 mt-3">
                                <label class="form-label">Type</label>
                                <div>
                                    <select class="form-select" v-model="ospfarea.type">

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
                           
                           <div class="form-check form-switch col-6">
                                    
                                    <input class="form-check-input" type="checkbox" role="switch" @click="toggleDisabled()" :checked="ospfarea.disabled!=undefined && ospfarea.disabled != 'false'">
                                    <label class="form-check-label" for="flexSwitchCheckDefault" > Disabled</label>
                                    
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