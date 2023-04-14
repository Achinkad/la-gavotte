<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useIPStore } from '../../stores/ip.js'

import CreateAddress from '../../components/IP/CreateAddress.vue' // Component -> Create Address
import EditAddress from '../../components/IP/EditAddress.vue' // Component -> Edit Address

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const ipStore = useIPStore()

const routerIdentification = ref('-') // Current Router
const selectedAddress = ref(null) // Selected Address

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// IP Addresses
const loadAddresses = ((data) => { ipStore.loadAddresses(data) })
const addresses = computed(() => { return ipStore.getAddresses() })

// Delete IP Address
const deleteAddress = ((address) => { ipStore.deleteAddress(address, routerIdentification.value) })

// Edit IP Address
const editAddress = ((address) => { selectedAddress.value = address })

const newAddress = () => { selectedAddress.value = null }

// Toogle Disabled IP Addresses
const toogleDisabled = ((address) => {
    let body = {
        router: routerIdentification.value,
        disabled: address.disabled
    }

    axiosApi.patch('ip/address/active/' + address['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The wireless network was activated with success.')
        } else {
            notyf.success('The wireless network was disabled with success.')
        }

        loadAddresses({ id: body.router })
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
})

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load IP Addresses
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
    loadAddresses(data)
})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <select class="form-select" v-model="routerIdentification">
                        <option value="-" selected hidden disabled>Select a router</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">DNS</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Title here</h4>
                </div>
                <div class="card-body pt-0">
                    <p>Lorem ipsum.</p>
                </div>
            </div>
        </div>
    </div>
</template>
