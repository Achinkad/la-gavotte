<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useIPStore } from '../../stores/ip.js'

import CreateDHCP from '../../components/IP/CreateDHCP.vue' // Component -> Create DHCP
import EditDHCP from '../../components/IP/EditDHCP.vue' // Component -> Edit DHCP

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const ipStore = useIPStore()

const routerIdentification = ref('-') // Current Router
const selectedDHCP = ref(null) // Selected DHCP

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// DHCP Servers
const loadDHCP = ((data) => { ipStore.loadDHCP(data) })
const dhcpServers = computed(() => { return ipStore.getDHCP() })

// Delete DHCP Server
const deleteDHCP = ((dhcpServer) => { ipStore.deleteDHCP(dhcpServer, routerIdentification.value) })

// Edit DHCP Server
const editDHCP = ((dhcpServer) => { selectedDHCP.value = dhcpServer })

const newDHCP = () => { selectedDHCP.value = null }

// Toogle Disabled Route
const toogleDisabled = ((dhcpServer) => {
    let body = {
        router: routerIdentification.value,
        disabled: dhcpServer.disabled
    }

    axiosApi.patch('ip/dhcp-server/active/' + dhcpServer['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The DHCP server was activated with success.')
        } else {
            notyf.success('The DHCP server was disabled with success.')
        }

        loadDHCP({ id: body.router })
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
})

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load Routes
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
    loadDHCP(data)
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
                <h2 class="p-title">DHCP Servers</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">List of DHCP servers available</h4>
                            <div class="d-flex justify-content-end" v-if="selectedDHCP != null">
                                <div class="px-1">
                                    <button type="button" class="btn btn-primary" @click="newDHCP()">Switch to Create Mode</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:10%">#ID</th>
                                        <th>Name</th>
                                        <th>Interface</th>
                                        <th>Address Pool</th>
                                        <th class="text-center">Activated</th>
                                        <th class="text-center" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="dhcpServers.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="dhcpServer in dhcpServers" :key="dhcpServer['.id']">
                                        <td class="align-middle">#{{ dhcpServer['.id'].substring(1) }}</td>
                                        <td>{{ dhcpServer.name }}</td>
                                        <td>{{ dhcpServer.interface }}</td>
                                        <td>{{ dhcpServer['address-pool'] }}</td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(dhcpServer)" :checked="dhcpServer.disabled == 'false'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editDHCP(dhcpServer)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteDHCP(dhcpServer)">
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
            <CreateDHCP v-if="!selectedDHCP" :dhcpServers="dhcpServers" :router="parseInt(routerIdentification)"/>
            <EditDHCP v-if="selectedDHCP" :dhcpServer="selectedDHCP" :dhcpServers="dhcpServers" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
