<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useVPNStore } from '../../stores/vpn.js'

const notyf = inject('notyf') // Notyf

const VPNStore = useVPNStore() // VPN Pinia Store

const props = defineProps({ router: { type: Number } }) // Router IP

const routerIdentification = toRef(props, 'router')

const vpnServer = ref({
    name: null,
    mtu: 1420,
    port: 13231
})

const createServerVPN = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('name', vpnServer.value.name)
    formData.append('mtu', vpnServer.value.mtu)
    formData.append('port', vpnServer.value.port)

    VPNStore.createServerVPN(formData)
    Object.entries(vpnServer.value).forEach(([i]) => { vpnServer.value[i] = null })
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Create a new VPN Server</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" novalidate @submit.prevent="createServerVPN">
                <div class="col-12">
                    <label for="name" class="form-label">VPN Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a name"
                    v-model="vpnServer.name" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="network" class="form-label">Listen Port <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="network" placeholder="Enter a port"
                    v-model="vpnServer.port" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="network" class="form-label">MTU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="network" placeholder="Enter a value"
                    v-model="vpnServer.mtu" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary" :disabled="isNaN(routerIdentification)">Add VPN Server</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
