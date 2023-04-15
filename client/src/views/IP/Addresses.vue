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
                        <option value="-" selected hidden disabled v-if="routers.length > 0">Select a router</option>
                        <option value="-" selected hidden disabled v-else>Loading routers...</option>
                        <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                    </select>
                </div>
                <h2 class="p-title">Addresses</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">List of addresses</h4>
                            <div class="d-flex justify-content-end" v-if="selectedAddress != null">
                                <div class="px-1">
                                    <button type="button" class="btn btn-primary" @click="newAddress()">Switch to Create Mode</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:10%">#ID</th>
                                        <th>IP Address</th>
                                        <th>Network</th>
                                        <th>Interface</th>
                                        <th class="text-center" style="width:12%">Activated</th>
                                        <th class="text-center" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="addresses.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="address in addresses" :key="address['.id']">
                                        <td class="align-middle">#{{ address['.id'].substring(1) }}</td>
                                        <td>{{ address.address }}</td>
                                        <td>{{ address.network }}</td>
                                        <td>{{ address['actual-interface'] }}</td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(address)" :checked="address.disabled == 'false'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editAddress(address)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteAddress(address)">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <CreateAddress v-if="!selectedAddress" :router="parseInt(routerIdentification)"/>
            <EditAddress v-if="selectedAddress" :address="selectedAddress" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
