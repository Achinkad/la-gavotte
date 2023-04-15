<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useVPNStore } from '../../stores/vpn.js'

import CreateClient from '../../components/VPN/CreateClient.vue' // Component -> Create VPN
import EditClient from '../../components/VPN/EditClient.vue' // Component -> Edit VPN

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const VPNStore = useVPNStore()

const routerIdentification = ref('-') // Current Router
const selectedVPN = ref(null) // Selected VPN Client

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// VPN Clients
const loadClientsVPN = ((data) => { VPNStore.loadClientsVPN(data) })
const vpnClients = computed(() => { return VPNStore.getClientVPN() })

// Delete VPN Client
const deleteVPN = ((vpnClient) => { VPNStore.deleteClientVPN(vpnClient, routerIdentification.value) })

// Edit VPN Client
const editVPN = ((vpnClient) => { selectedVPN.value = vpnClient })

const newVPN = () => { selectedVPN.value = null }

// Toogle Disabled VPN Client
const toogleDisabled = ((vpnClient) => {
    let body = {
        router: routerIdentification.value,
        disabled: vpnClient.disabled
    }

    axiosApi.patch('vpn/client/active/' + vpnClient['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The VPN client was activated with success.')
        } else {
            notyf.success('The VPN client was disabled with success.')
        }

        loadClientsVPN({ id: body.router })
    }).catch((error) => {
        console.log(error)
        notyf.error('Oops, an error has occurred.')
    })
})

// Copy VPN Server into clipboard
const copy = ((vpnClient) => {
    navigator.clipboard.writeText(vpnClient['public-key'])
    notyf.open({type: 'info', message: 'The public key is now on your clipboard. Go paste it!'})
})

const vpnAllowedAddresses = ((vpnClient) => {
    let allowedAddresses = ['-']
    if (vpnClient['allowed-address'].length > 0) {
        allowedAddresses.splice(0, allowedAddresses.length)
        allowedAddresses = vpnClient['allowed-address'].split(",")
    }
    return allowedAddresses
})

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load VPN Clients
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
    loadClientsVPN(data)
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
                <h2 class="p-title">VPN Clients - <a href="https://www.wireguard.com/" target="_blank" style="color:#374151;">WireGuard</a> <i class="bi bi-link-45deg" style="position:relative;top:1px;"></i></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">VPN Clients Available</h4>
                            <div class="d-flex justify-content-end" v-if="selectedVPN != null">
                                <div class="px-1">
                                    <button type="button" class="btn btn-primary" @click="newVPN()">Switch to Create Mode</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:7%">#ID</th>
                                        <th style="width:15%">Interface</th>
                                        <th style="width:15%">Allowed Addresses</th>
                                        <th style="width:15%">Endpoint</th>
                                        <th>Public Key*</th>
                                        <th class="text-center" style="width:12%">Activated</th>
                                        <th class="text-center" style="width:13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="vpnClients.length == 0">
                                        <td colspan="7" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="vpnClient in vpnClients" :key="vpnClient['.id']">
                                        <td class="align-middle">#{{ vpnClient['.id'].substring(1) }}</td>
                                        <td>{{ vpnClient.interface }}</td>
                                        <td>
                                            <span v-for="a in vpnAllowedAddresses(vpnClient)">{{ a }} <br> </span>
                                        </td>
                                        <td>{{ vpnClient['endpoint-address'] }}:{{ vpnClient['endpoint-port'] }}</td>
                                        <td @click="copy(vpnClient)" title="Click to copy to clipboard"
                                            style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:1px; cursor:pointer;"><u>{{ vpnClient['public-key'] }}</u></td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(vpnClient)" :checked="vpnClient.disabled == 'false'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editVPN(vpnClient)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteVPN(vpnClient)">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="callout">
                        <b>*Note:</b> You can click in the public key to copy it into your clipboard!
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <CreateClient v-if="!selectedVPN" :router="parseInt(routerIdentification)"/>
            <EditClient v-if="selectedVPN" :vpnClient="selectedVPN" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
