<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useIPStore } from '../../stores/ip.js'
import { useInterfaceStore } from '../../stores/interface.js'

const notyf = inject('notyf') // Notyf

const ipStore = useIPStore() // IP Pinia Store
const interfaceStore = useInterfaceStore() // Interface Pinia Store

const props = defineProps({ router: { type: Number } }) // Router IP

const routerIdentification = toRef(props, 'router') // Router IP Reference 
const interfaceType = ref("all") // Interface type

const loadInterfaces = ((routerIdentification) => { interfaceStore.loadInterfaces(routerIdentification, interfaceType) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const address = ref({
    address: null,
    network: null,
    interface: null
})

const createAddress = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('address', address.value.address)
    formData.append('interface', address.value.interface)
    address.value.network ? formData.append('network', address.value.network) : formData.append('network', address.value.address)

    ipStore.createAddress(formData)
    Object.entries(address.value).forEach(([i]) => { address.value[i] = null })
})

// Load Interfaces
onBeforeMount(() => {
    if(!isNaN(routerIdentification.value)) loadInterfaces(routerIdentification)
})

// Load Interfaces based on selected router
watch(routerIdentification, () => {
    if(!isNaN(routerIdentification.value)) loadInterfaces(routerIdentification)
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Create a new address</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="createAddress">
                <div class="col-6">
                    <label for="address" class="form-label">IP Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" placeholder="Enter an address"
                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:/(?:3[0-2]|[12]?[0-9]))?$"
                    v-model="address.address" required>
                </div>
                <div class="col-6">
                    <label for="network" class="form-label">Network address</label>
                    <input type="text" class="form-control" id="network" placeholder="Enter a network address"
                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                    v-model="address.network" >
                </div>
                <div class="col-6">
                    <label for="interface" class="form-label">Interface <span class="text-danger">*</span></label>
                    <select class="form-select" id="interface" v-model='address.interface' :disabled="isNaN(routerIdentification)">
                        <option value="null" selected hidden disabled>Select a interface</option>
                        <option v-for="i in interfaces" :key="i" :value="i.name" :disabled="i.disabled == 'true'">{{i.name}}</option>
                    </select>
                   
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end " v-if="isNaN(routerIdentification)"><u>Note: You must select a router</u>&nbspto add a new Address.</div>
                <div class="col-12 mt-4 d-flex justify-content-end" v-else>
                
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Add Address</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
