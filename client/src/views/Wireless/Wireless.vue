<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'
import { useRouter } from 'vue-router'

import { useRouterStore } from '../../stores/router.js'
import { useWirelessStore } from '../../stores/wireless.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const router = useRouter()
const routerStore = useRouterStore()
const wirelessStore = useWirelessStore()

const routerIdentification = ref("-")

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// Wireless Networks
const loadWirelessNetworks = ((data) => { wirelessStore.loadWirelessNetworks(data) })
const wirelessNetworks = computed(() => { return wirelessStore.getWirelessNetworks() })

// Security Profiles
const loadSecurityProfiles = ((data) => { wirelessStore.loadSecurityProfiles(data) })
const securityProfiles = computed(() => { return wirelessStore.getSecurityProfiles() })

// Toogle Disabled Wireless Networks
const toogleDisabled = ((wirelessNetwork) => {
    let body = {
        router: routerIdentification.value,
        disabled: wirelessNetwork.disabled
    }

    axiosApi.patch('wireless/' + wirelessNetwork['.id'], { body }).then((response) => {
        notyf.success('The wireless network was activated with success.')
        loadWirelessNetworks({ id: body.router })
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
})

const showWirelessNetwork = (networkId) => { router.push({ name: "WirelessNetwork", params: { id: networkId } }) }

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load Wireless Networks & Security Profiles
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }

    loadWirelessNetworks(data)
    loadSecurityProfiles(data)
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
                <h2 class="p-title">Wireless &amp; Security Profiles</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
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
                                        <th>#ID</th>
                                        <th>Name</th>
                                        <th>SSID</th>
                                        <th>Security profile</th>
                                        <th class="text-center">Activated</th>
                                        <th class="text-center" style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="wirelessNetworks.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">No data available.</td>
                                    </tr>
                                    <tr v-for="wirelessNetwork in wirelessNetworks" :key="wirelessNetwork['.id']">
                                        <td class="align-middle">#{{ wirelessNetwork['.id'].substring(1) }}</td>
                                        <td>{{ wirelessNetwork.name }}</td>
                                        <td>{{ wirelessNetwork.ssid }}</td>
                                        <td>{{ wirelessNetwork['security-profile']}}</td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(wirelessNetwork)" :checked="wirelessNetwork.disabled == 'true'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="View" @click="showWirelessNetwork(wirelessNetwork['.id'].substring(1))">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Edit">
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
        <div class="col-md-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Security profiles</h4>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#ID</th>
                                        <th>Name</th>
                                        <th>Mode</th>
                                        <th>Authentication Type</th>
                                        <th class="text-center" style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="securityProfiles.length == 0">
                                        <td colspan="5" class="text-center" style="height:55px!important;">No data available.</td>
                                    </tr>
                                    <tr v-for="securityProfile in securityProfiles" :key="securityProfile.id">
                                        <td class="align-middle">#{{ securityProfile['.id'].substring(1) }}</td>
                                        <td>{{ securityProfile.name }}</td>
                                        <td>{{ securityProfile.mode }}</td>
                                        <td v-if="securityProfile['authentication-types'].length == 0"> - </td>
                                        <td v-else>{{ securityProfile['authentication-types'] }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="View">
                                                    <i class="bi bi-eye"></i>
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
</template>
