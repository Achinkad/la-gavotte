<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useWirelessStore } from '../../stores/wireless.js'

import WirelessNetwork from '../../components/Wireless/WirelessNetwork.vue' // Component -> Show/Edit Network

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const wirelessStore = useWirelessStore()

const routerIdentification = ref('-') // Current Router
const selectedNetwork = ref(null) // Selected Network

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// Wireless Networks
const loadWirelessNetworks = ((data) => { wirelessStore.loadWirelessNetworks(data) })
const wirelessNetworks = computed(() => { return wirelessStore.getWirelessNetworks() })

// Toogle Disabled Wireless Networks
const toogleDisabled = ((wirelessNetwork) => {
    let body = {
        router: routerIdentification.value,
        disabled: wirelessNetwork.disabled
    }

    axiosApi.patch('wireless/active/' + wirelessNetwork['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The wireless network was activated with success.')
        } else {
            notyf.success('The wireless network was disabled with success.')
        }

        loadWirelessNetworks({ id: body.router })
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
})

const editWirelessNetwork = (network) => { selectedNetwork.value = network } // Edit Network

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load Wireless Networks
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
    loadWirelessNetworks(data)
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
                <h2 class="p-title">Wireless Networks</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Wireless networks available</h4>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 7%">#ID</th>
                                        <th>Router IP</th>
                                        <th>Name</th>
                                        <th>SSID</th>
                                        <th>MAC Address</th>
                                        <th>Security profile</th>
                                        <th class="text-center">Activated</th>
                                        <th class="text-center" style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="wirelessNetworks.length == 0">
                                        <td colspan="8" class="text-center" style="height:55px!important;">No data available. Please, select a router in the top right corner.</td>
                                    </tr>
                                    <tr v-for="wirelessNetwork in wirelessNetworks" :key="wirelessNetwork['.id']">
                                        <td class="align-middle">#{{ wirelessNetwork['.id'].substring(1) }}</td>
                                        <td>{{ wirelessNetwork.routerAddress }}</td>
                                        <td>{{ wirelessNetwork.name }}</td>
                                        <td>{{ wirelessNetwork.ssid }}</td>
                                        <td>{{ wirelessNetwork['mac-address'] }}</td>
                                        <td>{{ wirelessNetwork['security-profile']}}</td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(wirelessNetwork)" :checked="wirelessNetwork.disabled == 'false'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editWirelessNetwork(wirelessNetwork)">
                                                    <i class="bi bi-pencil"></i>
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
    </div>
    <WirelessNetwork v-if="selectedNetwork" :network="selectedNetwork" :router="parseInt(routerIdentification)"/>
</template>
