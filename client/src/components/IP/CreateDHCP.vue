<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useIPStore } from '../../stores/ip.js'
import { useInterfaceStore } from '../../stores/interface.js'

const notyf = inject('notyf') // Notyf

const ipStore = useIPStore() // IP Pinia Store
const interfaceStore = useInterfaceStore() // Interface Pinia Store

const props = defineProps({
    router: { type: Number },
    dhcpServers: { type: Object }
})

const routerIdentification = toRef(props, 'router') // Router IP Reference because BERTOLO IS DUMB!
const interfaceType = ref("all") // Interface type because BERTOLO IS DUMB!

const loadInterfaces = ((routerIdentification) => { interfaceStore.loadInterfaces(routerIdentification, interfaceType) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const verifyInterfaces = (() => {
    let interfacesToUse = []

    interfaces.value.forEach((i) => {
        props.dhcpServers.forEach((j) => {
            if (i.name != j.interface && !interfacesToUse.includes(i) && i.type != 'wg') interfacesToUse.push(i)
        })
    })

    return interfacesToUse
})

const dhcpServer = ref({
    name: null,
    interface: null,
    leaseTime: 10,
    addressPool: null
})

const createDHCP = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('name', dhcpServer.value.name)
    formData.append('interface', dhcpServer.value.interface)
    formData.append('leaseTime', dhcpServer.value.leaseTime + 'm')
    formData.append('addressPool', dhcpServer.value.addressPool)

    ipStore.createDHCP(formData)
    Object.entries(dhcpServer.value).forEach(([i]) => { dhcpServer.value[i] = null })
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
            <h4 class="header-title">Create a new DHCP Server</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="createDHCP">
                <div class="col-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a name"
                    v-model="dhcpServer.name" required :disabled="isNaN(routerIdentification)">
                </div>
                <div class="col-6">
                    <label for="leaseTime" class="form-label">Lease Time</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="leaseTime" placeholder="Enter a lease time" v-model="dhcpServer.leaseTime" required :disabled="isNaN(routerIdentification)">
                        <span class="input-group-text">Minutes</span>
                    </div>
                </div>
                <div class="col-6">
                    <label for="interface" class="form-label">Interface <span class="text-danger">*</span></label>
                    <select class="form-select" id="interface" v-model='dhcpServer.interface' :disabled="isNaN(routerIdentification)">
                        <option value="null" selected hidden disabled>Select a interface</option>
                        <option v-for="i in verifyInterfaces()" :key="i" :value="i.name" :disabled="i.disabled == 'true' || i.running == 'false'">{{i.name}}</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="addressPool" class="form-label">Address Pool <span class="text-danger">*</span></label>
                    <select class="form-select" id="addressPool" v-model='dhcpServer.addressPool' :disabled="isNaN(routerIdentification)">
                        <option value="null" selected hidden disabled>Select an address pool</option>
                        <option value="static-only">Static only</option>
                        <option value="dhcp_pool0">DHCP Pool 0</option>
                    </select>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1" :disabled="isNaN(routerIdentification)">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary" :disabled="isNaN(routerIdentification)">Add DHCP Server</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="callout" v-if="isNaN(routerIdentification)">
        <b>Note</b>: You must select a router to add a new DHCP server.
    </div>
</template>
