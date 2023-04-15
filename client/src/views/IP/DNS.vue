<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useIPStore } from '../../stores/ip.js'

import EditDNS from '../../components/IP/EditDNS.vue' // Component -> Edit DNS

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const ipStore = useIPStore()

const routerIdentification = ref('-') // Current Router
const selectedDNS = ref(null) // Selected DNS Server

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// DNS Servers
const loadDNSServers = ((data) => { ipStore.loadDNS(data) })
const dnsServer = computed(() => { return ipStore.getDNS() })

// Edit DNS Server
const editDNS = ((dnsServer) => { selectedDNS.value = dnsServer })

const dnsServerDynamicServers = ((dnsServer) => {
    let dynamicServers = ['-']
    if (dnsServer['dynamic-servers'].length > 0) {
        dynamicServers.splice(0, dynamicServers.length)
        dynamicServers = dnsServer['dynamic-servers'].split(",")
    }
    return dynamicServers
})

const dnsServerServers = ((dnsServer) => {
    let servers = ['-']
    if (dnsServer['servers'].length > 0) {
        servers.splice(0, servers.length)
        servers = dnsServer['servers'].split(",")
    }
    return servers
})

// Toogle Disabled DNS Server
const toogleDisabled = ((dnsServer) => {
    let body = {
        router: routerIdentification.value,
        disabled: dnsServer['allow-remote-requests']
    }

    axiosApi.patch('ip/dns/active', body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The DNS server was disabled with success.')
        } else {
            notyf.success('The DNS server was activated with success.')
        }

        loadDNSServers({ id: body.router })
    }).catch((error) => {
        console.log(error)
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
    loadDNSServers(data)
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
                <h2 class="p-title">DNS Server</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">DNS Server Parameters</h4>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:10%">#ID</th>
                                        <th>Servers</th>
                                        <th>Dynamic Servers</th>
                                        <th>Max. TCP Sessions</th>
                                        <th class="text-center">Activated</th>
                                        <th class="text-center" style="width:12%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="dnsServer.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-else>
                                        <td class="align-middle">#1</td>
                                        <td>
                                            <span v-for="d in dnsServerServers(dnsServer)">{{ d }} <br> </span>
                                        </td>
                                        <td>
                                            <span v-for="d in dnsServerDynamicServers(dnsServer)">{{ d }} <br> </span>
                                        </td>
                                        <td>{{ dnsServer['max-concurrent-tcp-sessions'] }}</td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(dnsServer)" :checked="dnsServer['allow-remote-requests'] == 'true'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editDNS(dnsServer)">
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
        <div class="col-md-5">
            <div v-if="!selectedDNS" class="callout mt-0">
                To edit a DNS server, click on the corresponding icon in the table. Be sure to double-check all changes before saving to avoid any disruptions to your DNS service on your device.
            </div>
            <EditDNS v-if="selectedDNS" :dnsServer="selectedDNS" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
