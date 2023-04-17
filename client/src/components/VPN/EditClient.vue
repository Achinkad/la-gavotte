<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useVPNStore } from '../../stores/vpn.js'
import { useInterfaceStore } from '../../stores/interface.js'

const notyf = inject('notyf') // Notyf

const VPNStore = useVPNStore() // VPN Pinia Store
const interfaceStore = useInterfaceStore() // Interface Pinia Store

const props = defineProps({
    router: { type: Number },
    vpnClient: { type: Object }
})

const routerIdentification = toRef(props, 'router') // Router IP Reference because BERTOLO IS DUMB!
const interfaceType = ref("all") // Interface type because BERTOLO IS DUMB!

const loadInterfaces = ((routerIdentification) => { interfaceStore.loadInterfaces(routerIdentification, interfaceType) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const loadServersVPN = ((data) => { VPNStore.loadServersVPN(data) })

const editClientVPN = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('id', props.vpnClient['.id'])
    formData.append('interface', props.vpnClient.interface)
    formData.append('publicKey', props.vpnClient['public-key'])
    formData.append('allowedAddresses', props.vpnClient['allowed-address'])
    formData.append('endpointAddress', props.vpnClient['endpoint-address'])
    formData.append('endpointPort', props.vpnClient['endpoint-port'])

    VPNStore.editClientVPN(formData)
})

// Load Interfaces
onBeforeMount(() => {
    if(!isNaN(routerIdentification.value)) loadInterfaces(routerIdentification)
})

// Load Interfaces based on selected router
watch(routerIdentification, () => {
    if(!isNaN(routerIdentification.value)) loadInterfaces(routerIdentification)

    let data = { id: routerIdentification.value }
    loadServersVPN(data)
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">You're editing the vpn client {{ vpnClient['.id'] }}</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="editClientVPN">
                <div class="col-12">
                    <label for="name" class="form-label">Interface <span class="text-danger">*</span></label>
                    <select class="form-select" id="interface" v-model='vpnClient.interface' :disabled="isNaN(routerIdentification)">
                        <option value="null" selected hidden disabled>Select a interface</option>
                        <option v-for="i in interfaces" :key="i" :value="i.name" :disabled="i.disabled == 'true'" :hidden="i.type != 'wg'">{{i.name}}</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="endpointAddress" class="form-label">Enpoint Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="endpointAddress" placeholder="Enter an address"
                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                    v-model="vpnClient['endpoint-address']" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="endpointPort" class="form-label">Endpoint Port <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="endpointPort" placeholder="Enter a port"
                    pattern="^([1-9]|[1-9][0-9]|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                    v-model="vpnClient['endpoint-port']" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-12">
                    <label for="allowedAddresses" class="form-label">Allowed Addresses <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="allowedAddresses" placeholder="Enter an address"
                    v-model="vpnClient['allowed-address']" required :disabled="isNaN(routerIdentification)">
                    <div id="addressHelp" class="form-text">Please <u>use commas</u> to separate your addresses.</div>
                </div>
                <div class="col-12">
                    <label for="network" class="form-label">Public Key <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="network" placeholder="Enter the public key of your device"
                    v-model="vpnClient['public-key']" required :disabled="isNaN(routerIdentification)">
                </div>

                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1" :disabled="isNaN(routerIdentification)">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary" :disabled="isNaN(routerIdentification)">Edit VPN Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="callout" v-if="isNaN(routerIdentification)">
        <b>Note</b>: You must select a router to add a new VPN client.
    </div>
</template>
