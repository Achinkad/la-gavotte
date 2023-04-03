<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'
import { useRouterStore } from '../stores/router.js'

const axiosApi = inject('axiosApi')

const routerStore = useRouterStore()

const routerIdentification = ref("-")
const securityProfiles = ref([])
const wirelessNetworks = ref([])

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

onBeforeMount(() => {
    loadRouters()
})

watch(routerIdentification, () => {
    let options = { id: routerIdentification.value }

    axiosApi.get('wireless/security-profiles/', {
        params: options
    }).then((response) => {
        securityProfiles.value = response.data
    }).catch((error) => {
        console.log(error)
    })

    axiosApi.get('wireless', {
        params: options
    }).then((response) => {
        wirelessNetworks.value = response.data
    }).catch((error) => {
        console.log(error)
    })
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
                <h2 class="p-title">Wireless</h2>
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
                                        <th>Running</th>
                                        <th class="text-center" style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="wirelessNetworks.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">No data available.</td>
                                    </tr>
                                    <tr v-for="wirelessNetwork in wirelessNetworks" :key="wirelessNetwork.id">
                                        <td class="align-middle">#{{ wirelessNetwork['.id'].substring(1) }}</td>
                                        <td>{{ wirelessNetwork.name }}</td>
                                        <td>{{ wirelessNetwork.ssid }}</td>
                                        <td>{{ wirelessNetwork['security-profile']}}</td>
                                        <td>{{ wirelessNetwork.running }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light" title="View">
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
                                        <td>{{ securityProfile['authentication-types'] }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light" title="View">
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
