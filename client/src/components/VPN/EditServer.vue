<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useVPNStore } from '../../stores/vpn.js'

const notyf = inject('notyf') // Notyf

const VPNStore = useVPNStore() // VPN Pinia Store

const props = defineProps({
    router: { type: Number },
    vpnServer : { type: Object }
})

const routerIdentification = toRef(props, 'router')

const editServerVPN = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('id', props.vpnServer['.id'])
    formData.append('name', props.vpnServer.name)
    formData.append('mtu', props.vpnServer.mtu)
    formData.append('port', props.vpnServer['listen-port'])

    VPNStore.editServerVPN(formData)
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">You're editing the vpn server {{ vpnServer['.id'] }}</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" novalidate @submit.prevent="editServerVPN">
                <div class="col-6">
                    <label for="name" class="form-label">VPN Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a name"
                    v-model="vpnServer.name" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="network" class="form-label">Listen Port <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="network" placeholder="Enter a port"
                    v-model="vpnServer['listen-port']" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="network" class="form-label">MTU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="network" placeholder="Enter a value"
                    pattern="^([6][4-9]|[7-9]\d{1}|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                    v-model="vpnServer.mtu" required :disabled="isNaN(routerIdentification)">
                    <div class="form-text">Value range: 64 - 65535.</div>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1" :disabled="isNaN(routerIdentification)">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary" :disabled="isNaN(routerIdentification)">Edit VPN Server</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="callout" v-if="isNaN(routerIdentification)">
        <b>Note</b>: You must select a router to edit a VPN server.
    </div>
</template>
