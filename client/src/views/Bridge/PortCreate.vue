<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'

const axiosApi = inject('axiosApi')





onBeforeMount(() => {

})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create Port</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                        
                           <div class="px-1">
                                <router-link :to="{ name: 'PortCreate'}"
                                                    :title="`Create Port`">
                                    <button type="submit" class="btn btn-primary">Add Port</button>
                                </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Connected Ports</h4>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Add Port</button>
                    </div>
                </div>
                <div class="card-body pt-0">
               
                   
                </div>
            </div>
        </div>
    </div>
</template>
