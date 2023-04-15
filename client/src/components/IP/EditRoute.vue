<script setup>
import { ref, inject, computed, onBeforeMount, watch, toRef } from 'vue'

import { useIPStore } from '../../stores/ip.js'
import { useInterfaceStore } from '../../stores/interface.js'

const notyf = inject('notyf') // Notyf

const ipStore = useIPStore() // IP Pinia Store
const interfaceStore = useInterfaceStore() // Interface Pinia Store

const props = defineProps({
    router: { type: Number }, // Router IP
    route: { type: Object } // Selected Route
})

const routerIdentification = toRef(props, 'router') // Router IP Reference because BERTOLO IS DUMB!
const interfaceType = ref("all") // Interface type because BERTOLO IS DUMB!
const currentRouterIP = toRef(props, 'address.routerID')

const loadInterfaces = ((routerIdentification) => { interfaceStore.loadInterfaces(routerIdentification, interfaceType) })
const interfaces = computed(() => { return interfaceStore.getInterfaces() })

const editRoute = (() => {
    let formData = new FormData()

    formData.append('id', props.route['.id'])
    formData.append('router', props.router)
    formData.append('destination', props.route['dst-address'])
    formData.append('gateway', props.route.gateway)
    props.route.distance ? formData.append('distance', props.route.distance) : formData.append('distance', 0)

    ipStore.editRoute(formData)
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
            <h4 class="header-title">You're editing the route {{ route['.id'] }}</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="editRoute">
                <div class="col-6">
                    <label for="address" class="form-label">Destination address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" placeholder="Enter a destination address"
                    pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(?:/(?:3[0-2]|[12]?[0-9]))?$"
                    v-model="route['dst-address']" required>
                </div>
                <div class="col-6">
                    <label for="distance" class="form-label">Distance</label>
                    <input type="text" class="form-control" id="distance" placeholder="Enter a distance"
                    pattern="^(1\d{2}|2[0-4]\d|25[0-5]|[1-9]\d?)$"
                    v-model="route.distance" required>
                </div>
                <div class="col-6">
                    <label for="gateway" class="form-label">Gateway</label>
                    <input type="text" class="form-control" list="listGateway" id="gateway" placeholder="Enter a gateway" v-model="route.gateway" required>
                    <datalist id="listGateway">
                        <option v-for="i in interfaces" :key="i" :value="i.name" :disabled="i.disabled == 'true'">{{i.name}}</option>
                    </datalist>
                    <div id="interfaceSelectHelp" class="form-text">Enter an interface or an address (double-click). <u>You must select a router to choose an interface</u>.</div>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Edit Route</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
