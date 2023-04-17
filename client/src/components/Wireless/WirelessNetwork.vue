<script setup>
import { toRef, ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useWirelessStore } from '../../stores/wireless.js'

import Frequency from './SelectFrequency.vue' // Component -> Select for Frequency & Channel Width

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const wirelessStore = useWirelessStore()

const routerIdentification = toRef(props, 'router') // Router IP Reference 

// Security Profiles
const loadSecurityProfiles = (() => { wirelessStore.loadSecurityProfiles({ id: props.network.routerId }) })
const securityProfiles = computed(() => { return wirelessStore.getSecurityProfiles() })

const props = defineProps({
    network: { type: Object }, // Wireless Network
    router: { type: Number } // Router ID
})

const editWirelessNetwork = () => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('id', props.network[".id"])
    formData.append('name', props.network["name"])
    formData.append('ssid', props.network["ssid"])
    formData.append('macAddress', props.network["mac-address"])
    formData.append('mtu', props.network["mtu"])
    formData.append('mode', props.network["mode"])
    formData.append('band', props.network["band"])
    formData.append('channelWidth', props.network["channel-width"])
    formData.append('frequency', props.network["frequency"])
    formData.append('arp', props.network["arp"])
    formData.append('securityProfile', props.network['security-profile'])
    formData.append('wdsMode', props.network["wds-mode"])
    formData.append('wdsDefaultBridge', props.network["wds-default-bridge"])

    wirelessStore.editWirelessNetwork(formData) // Pinia Store -> Wireless Edit Function
}

// Load Routers
onBeforeMount(() => {
    loadSecurityProfiles()
})
</script>

<template>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">You're viewing the wireless network: {{ network.name }} </h4>
                        </div>
                        <div class="card-body pt-0">
                            <form class="row g-3 needs-validation" @submit.prevent="editWirelessNetwork">
                                <div class="col-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Insert a name"
                                    v-model='network.name' required>
                                </div>
                                <div class="col-3">
                                    <label for="ssid" class="form-label">SSID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ssid" placeholder="Insert a SSID"
                                    v-model='network.ssid' required>
                                </div>
                                <div class="col-3">
                                    <label for="mac" class="form-label">MAC Address<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mac" placeholder="Insert a MAC address"
                                    pattern="([0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2}"
                                    v-model='network["mac-address"]' required>
                                </div>
                                <div class="col-3">
                                    <label for="mtu" class="form-label">MTU [32-2290]<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="mtu" placeholder="Insert a MTU"
                                    pattern="^(3[2-9]|[4-9][0-9]|[1-9][0-9]{2}|[1-9]\d{3}|2[0-1][0-9]{2}|22[0-8][0-9]|2290)$"
                                    v-model='network.mtu' required>
                                </div>
                                <div class="col-3">
                                    <label for="selectMode" class="form-label">Mode <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectMode" v-model="network.mode">
                                        <option value="-" hidden disabled>Select a mode</option>
                                        <option :value='network.band' selected hidden disabled>{{ network.mode }}</option>
                                        <option value="alignment-only">alignment only</option>
                                        <option value="ap-bridge">ap bridge</option>
                                        <option value="bridge">bridge</option>
                                        <option value="nstreme-dual-slave">nstreme dual slave</option>
                                        <option value="station">station</option>
                                        <option value="station-bridge">station bridge</option>
                                        <option value="station-pseudobridge">station pseudobridge</option>
                                        <option value="station-pseudobridge-clone">station pseudobridge clone</option>
                                        <option value="station-wds">station wds</option>
                                        <option value="wds-slave">wds slave</option>
                                    </select>
                                </div>

                                <Frequency :network='network'/>

                                <div class="col-3">
                                    <label for="selectARP" class="form-label">ARP <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectARP" v-model="network.arp">
                                        <option value="-" selected hidden disabled>Select an ARP</option>
                                        <option :value='network.arp' selected hidden disabled>{{ network.arp }}</option>
                                        <option value="disabled">disabled</option>
                                        <option value="enabled">enabled</option>
                                        <option value="local-proxy-arp">local-proxy-arp</option>
                                        <option value="proxy-arp">proxy-arp</option>
                                        <option value="reply-only">reply-only</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="selectSecurityProfile" class="form-label">Security profile <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectSecurityProfile" v-model="network['security-profile']">
                                        <option value="-" selected hidden disabled>Select a security profile</option>
                                        <option :value='network["security-profile"]' selected hidden disabled>{{ network["security-profile"] }}</option>
                                        <option v-for="securityProfile in securityProfiles" :key="securityProfile['.id']" :value="securityProfile.name">{{ securityProfile.name }}</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="selectWDS" class="form-label">WDS <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectWDS" v-model='network["wds-mode"]'>
                                        <option value="-" selected hidden disabled>Select a WDS</option>
                                        <option :value='network["wds-mode"]' selected hidden disabled>{{ network["wds-mode"] }}</option>
                                        <option value="disabled">disabled</option>
                                        <option value="dynamic">dynamic</option>
                                        <option value="dynamic-mesh">dynamic mesh</option>
                                        <option value="static">static</option>
                                        <option value="static-mesh">static mesh</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="selectWDSBridge" class="form-label">WDS Default bridge <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectWDSBridge" v-model="network['wds-default-bridge']">
                                        <option value="-" selected hidden disabled>Select a bridge</option>
                                        <option :value='network["wds-default-bridge"]' selected hidden disabled>{{ network["wds-default-bridge"] }}</option>
                                        <option value="none">none</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-4 d-flex justify-content-end" v-if="isNaN(routerIdentification)"><u>Note: You must select a router</u>&nbspto edit a Wireless Network.</div>
                                <div class="col-12 mt-4 d-flex justify-content-end" v-else>
                                    <div class="px-1">
                                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                                    </div>
                                    <div class="px-1">
                                        <button type="submit" class="btn btn-primary">Edit Wireless Network</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
