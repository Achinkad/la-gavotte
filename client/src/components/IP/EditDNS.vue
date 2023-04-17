<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useIPStore } from '../../stores/ip.js'

const notyf = inject('notyf') // Notyf

const ipStore = useIPStore() // IP Pinia Store

const props = defineProps({
    router: { type: Number },
    dnsServer: { type: Object }
})

const routerIdentification = toRef(props, 'router')

const editDNS = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('servers', props.dnsServer.servers)
    formData.append('udpPacket', props.dnsServer['max-udp-packet-size'])
    formData.append('tcpSession', props.dnsServer['max-concurrent-tcp-sessions'])
    formData.append('queries', props.dnsServer['max-concurrent-queries'])

    ipStore.editDNS(formData)
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">You're editing the DNS server #1</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="editDNS">
                <div class="col-6">
                    <label for="server" class="form-label">Servers</label>
                    <input type="text" class="form-control" id="server" placeholder="Enter a server address"
                    v-model="dnsServer.servers">
                    <div id="serverHelp" class="form-text">Insert your servers <u>seperated by commas.</u></div>
                </div>
                <div class="col-6">
                    <label for="packetSize" class="form-label">Max. UDP Packet Size [64-65507]<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="packetSize" placeholder="Enter a value" 
                    
                    pattern="^([6][4-9]|[7-9]\d{1}|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|6550[0-7])$"
                    v-model="dnsServer['max-udp-packet-size']" required>
                </div>
                <div class="col-6">
                    <label for="tpc" class="form-label">Max. Concurrent TCP Session [0-4294967295] <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tpc" placeholder="Enter a value"
                    pattern="^(?:0|[1-9]\d{0,8}|[1-3]\d{0,9}|4[0-2]\d{0,8}|429496729[0-5])$"
                    v-model="dnsServer['max-concurrent-tcp-sessions']" required>
                </div>
                <div class="col-6">
                    <label for="queries" class="form-label">Max. Concurrent Queries [0-4294967295]<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="queries" placeholder="Enter a value" 
                    pattern="^(?:0|[1-9]\d{0,8}|[1-3]\d{0,9}|4[0-2]\d{0,8}|429496729[0-5])$"
                    v-model="dnsServer['max-concurrent-queries']" required>
                </div>

                <div class="col-12 mt-4 d-flex justify-content-end" v-if="isNaN(routerIdentification)"><u>Note: You must select a router</u>&nbspto edit a DNS Server.</div>
                <div class="col-12 mt-4 d-flex justify-content-end" v-else>
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Edit DNS Server</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
