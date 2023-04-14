<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useIPStore } from '../../stores/ip.js'
import { useInterfaceStore } from '../../stores/interface.js'

const notyf = inject('notyf') // Notyf

const ipStore = useIPStore() // IP Pinia Store
const interfaceStore = useInterfaceStore() // Interface Pinia Store

const props = defineProps({
    router: { type: Number },
    dhcpServer: { type: Object },
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

const editDHCP = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('id', props.dhcpServer['.id'])
    formData.append('name', props.dhcpServer.name)
    formData.append('interface', props.dhcpServer.interface)
    formData.append('leaseTime', props.dhcpServer['lease-time'])
    formData.append('addressPool', props.dhcpServer['address-pool'])

    ipStore.editDHCP(formData)
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
            <h4 class="header-title">You're editing the DHCP server {{ dhcpServer.name }}</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" novalidate @submit.prevent="editDHCP">
                <div class="col-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a name"
                    v-model="dhcpServer.name" required>
                </div>
                <div class="col-6">
                    <label for="leaseTime" class="form-label">Lease Time</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="leaseTime" placeholder="Enter a lease time" v-model="dhcpServer['lease-time']" required>
                        <span class="input-group-text">Minutes</span>
                    </div>
                </div>
                <div class="col-6">
                    <label for="interface" class="form-label">Interface <span class="text-danger">*</span></label>
                    <select class="form-select" id="interface" v-model='dhcpServer.interface' :disabled="isNaN(routerIdentification)">
                        <option value="null" selected hidden disabled>Select a interface</option>
                        <option v-for="i in verifyInterfaces()" :key="i" :value="i.name" :disabled="i.disabled == 'true' || i.running == 'false'">{{i.name}}</option>
                    </select>
                    <div id="interfaceSelectHelp" class="form-text"><u>You must select a router</u> to select an interface.</div>
                </div>
                <div class="col-6">
                    <label for="addressPool" class="form-label">Address Pool <span class="text-danger">*</span></label>
                    <select class="form-select" id="addressPool" v-model='dhcpServer["address-pool"]'>
                        <option value="null" selected hidden disabled>Select an address pool</option>
                        <option value="static-only">Static only</option>
                        <option value="dhcp_pool0">DHCP Pool 0</option>
                    </select>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Edit DHCP Server</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
