<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useVPNStore } from '../../stores/vpn.js'

import CreateServer from '../../components/VPN/CreateServer.vue' // Component -> Create VPN
import EditServer from '../../components/VPN/EditServer.vue' // Component -> Edit VPN

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const VPNStore = useVPNStore()

const routerIdentification = ref('-') // Current Router
const selectedVPN = ref(null) // Selected VPN Server

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// VPN Servers
const loadServersVPN = ((data) => { VPNStore.loadServersVPN(data) })
const vpnServers = computed(() => { return VPNStore.getServerVPN() })

// Delete VPN Server
const deleteVPN = ((vpnServer) => { VPNStore.deleteServerVPN(vpnServer, routerIdentification.value) })

// Edit VPN Server
const editVPN = ((vpnServer) => { selectedVPN.value = vpnServer })

const newVPN = () => { selectedVPN.value = null }

// Toogle Disabled VPN Server
const toogleDisabled = ((vpnServer) => {
    let body = {
        router: routerIdentification.value,
        disabled: vpnServer.disabled
    }

    axiosApi.patch('vpn/server/active/' + vpnServer['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The VPN server was activated with success.')
        } else {
            notyf.success('The VPN server was disabled with success.')
        }

        loadServersVPN({ id: body.router })
    }).catch((error) => {
        console.log(error)
        notyf.error('Oops, an error has occurred.')
    })
})

// Copy VPN Server into clipboard
const copy = ((vpnServer) => {
    navigator.clipboard.writeText(vpnServer['public-key'])
    notyf.open({type: 'info', message: 'The public key is now on your clipboard. Go paste it!'})
})

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load Routes
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
    loadServersVPN(data)
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
                <h2 class="p-title">VPN Server - <a href="https://www.wireguard.com/" target="_blank" style="color:#374151;">WireGuard</a> <i class="bi bi-link-45deg" style="position:relative;top:1px;"></i></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">VPN Servers Available</h4>
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
                                        <th style="width:8%">#ID</th>
                                        <th style="width:15%">Name</th>
                                        <th style="width:13%">List Port</th>
                                        <th>Public Key*</th>
                                        <th class="text-center" style="width:13%">Activated</th>
                                        <th class="text-center" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="vpnServers.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="vpnServer in vpnServers" :key="vpnServer['.id']">
                                        <td class="align-middle">#{{ vpnServer['.id'].substring(1) }}</td>
                                        <td>{{ vpnServer.name }}</td>
                                        <td>{{ vpnServer['listen-port'] }}</td>
                                        <td @click="copy(vpnServer)" title="Click to copy to clipboard"
                                            style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:1px; cursor:pointer;"><u>{{ vpnServer['public-key'] }}</u></td>
                                        <td>
                                            <div class="form-check form-switch text-center">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(vpnServer)" :checked="vpnServer.disabled == 'false'">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editVPN(vpnServer)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteVPN(vpnServer)">
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
        <div class="col-md-5">
            <CreateServer v-if="!selectedVPN" :router="parseInt(routerIdentification)"/>
            <EditServer v-if="selectedVPN" :vpnServer="selectedVPN" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
