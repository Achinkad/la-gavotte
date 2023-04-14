<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useWirelessStore } from '../../stores/wireless.js'

import CreateSecurityProfile from '../../components/Wireless/CreateSecurityProfile.vue' // Component -> Create Security Profile
import EditSecurityProfile from '../../components/Wireless/EditSecurityProfile.vue' // Component -> Edit Security Profile

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const wirelessStore = useWirelessStore()

const routerIdentification = ref('-') // Current Router
const selectedSecurityProfile = ref(null) // Selected Security Profile

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// Security Profiles
const loadSecurityProfiles = ((data) => { wirelessStore.loadSecurityProfiles(data) })
const securityProfiles = computed(() => { return wirelessStore.getSecurityProfiles() })

// Edit a Security Profile
const editSecurityProfile = (securityProfile) => { selectedSecurityProfile.value = securityProfile } // Edit Security Profile

const newSecurityProfile = () => { selectedSecurityProfile.value = null }

// Delete a Security Profile
const deleteSecurityProfile = ((securityProfile) => {
    wirelessStore.deleteSecurityProfile(securityProfile, routerIdentification.value)
})

// Load Routers
onBeforeMount(() => {
    loadRouters()
})

// Load Security Profiles
watch(routerIdentification, () => {
    let data = { id: routerIdentification.value }
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
                <h2 class="p-title">Security Profiles</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Security profiles available</h4>
                            <div class="d-flex justify-content-end" v-if="selectedSecurityProfile != null">
                                <div class="px-1">
                                    <button type="button" class="btn btn-primary" @click="newSecurityProfile()">Switch to Create Mode</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 10%">#ID</th>
                                        <th>Name</th>
                                        <th>Mode</th>
                                        <th>Authentication Type</th>
                                        <th class="text-center" style="width: 20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="securityProfiles.length == 0">
                                        <td colspan="5" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="securityProfile in securityProfiles" :key="securityProfile.id">
                                        <td class="align-middle">#{{ securityProfile['.id'].substring(1) }}</td>
                                        <td>{{ securityProfile.name }}</td>
                                        <td>{{ securityProfile.mode }}</td>
                                        <td v-if="securityProfile['authentication-types'].length == 0"> - </td>
                                        <td v-else>{{ securityProfile['authentication-types'] }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editSecurityProfile(securityProfile)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteSecurityProfile(securityProfile)">
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
            <CreateSecurityProfile v-if="!selectedSecurityProfile" :router="parseInt(routerIdentification)"/>
            <EditSecurityProfile v-if="selectedSecurityProfile" :securityProfile="selectedSecurityProfile" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
