<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'
import { useIPStore } from '../../stores/ip.js'

import CreateRoute from '../../components/IP/CreateRoute.vue' // Component -> Create Route
import EditRoute from '../../components/IP/EditRoute.vue' // Component -> Edit Route

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const ipStore = useIPStore()

const routerIdentification = ref('-') // Current Router
const selectedRoute = ref(null) // Selected Route

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// Routes
const loadRoutes = ((data) => { ipStore.loadRoutes(data) })
const routes = computed(() => { return ipStore.getRoutes() })

// Delete Route
const deleteRoute = ((route) => { ipStore.deleteRoute(route, routerIdentification.value) })

// Edit Route
const editRoute = ((route) => { selectedRoute.value = route })

const newRoute = () => { selectedRoute.value = null }

// Toogle Disabled Route
const toogleDisabled = ((route) => {
    let body = {
        router: routerIdentification.value,
        disabled: route.disabled
    }

    axiosApi.patch('ip/route/active/' + route['.id'], body).then((response) => {
        if (body.disabled == "true") {
            notyf.success('The route was activated with success.')
        } else {
            notyf.success('The route was disabled with success.')
        }

        loadRoutes({ id: body.router })
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
    loadRoutes(data)
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
                <h2 class="p-title">Routes</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">List of routes available</h4>
                            <div class="d-flex justify-content-end" v-if="selectedRoute != null">
                                <div class="px-1">
                                    <button type="button" class="btn btn-primary" @click="newRoute()">Switch to Create Mode</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:15%">#ID</th>
                                        <th>Dst. Address</th>
                                        <th>Gateway</th>
                                        <th style="width:13%">Distance</th>
                                        <th class="text-center">Disabled</th>
                                        <th class="text-center" style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="routes.length == 0">
                                        <td colspan="6" class="text-center" style="height:55px!important;">Please, select a router.</td>
                                    </tr>
                                    <tr v-for="route in routes" :key="route['.id']">
                                        <td class="align-middle">#{{ route['.id'].substring(1) }}</td>
                                        <td>{{ route['dst-address'] }}</td>
                                        <td>{{ route.gateway }}</td>
                                        <td>{{ route.distance }}</td>
                                        <td>
                                            <div class="form-check form-switch text-center" v-if="route.disabled">
                                                <div class="d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" @click="toogleDisabled(route)" :checked="route.disabled == 'true'">
                                                </div>
                                            </div>
                                            <div v-else class="text-center"> - </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="btn btn-xs btn-light table-button" title="Edit" @click="editRoute(route)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-xs btn-light table-button ms-2" title="Delete" @click="deleteRoute(route)">
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
            <CreateRoute v-if="!selectedRoute" :router="parseInt(routerIdentification)"/>
            <EditRoute v-if="selectedRoute" :route="selectedRoute" :router="parseInt(routerIdentification)"/>
        </div>
    </div>
</template>
