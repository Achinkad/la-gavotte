<script setup>
import { inject, computed, onBeforeMount } from 'vue'

import { useUserStore } from "../stores/user.js"
import { useRouterStore } from '../stores/router.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const userStore = useUserStore()
const routerStore = useRouterStore()

const loadRouters = ((dashboard = true) => { routerStore.loadRouters(dashboard) })
const routers = computed(() => { return routerStore.getRouters() })

const percentageOfActiveRouters = ((routers) => {
    let numActive = 0, numTotal = routers.length
    routers.forEach((i) => { if(!i.disabled) numActive++ })
    return ((numActive / numTotal) * 100).toFixed(2) + "%"
})

const backup = ((router) => {
    let body = {
        router: router.id,
        name: 'flash/LTI-VERY-NICE.backup'
    }

    axiosApi.post('backup', body).then((response) => {
        notyf.success('Backup done succesfully!')
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
})

onBeforeMount(() => {
    loadRouters()
})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Dashboard</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-5 col-lg-12">
            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-router-fill card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">Routers</h5>
                            <h3 class="mt-3 mb-3">{{ routers.length }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2" v-if="routers.length > 0"> {{ percentageOfActiveRouters(routers) }} </span>
                                <span class="text-success me-2" v-else> - </span>
                                <span class="text-nowrap">Active right now</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-database-fill card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">Backups</h5>
                            <h3 class="mt-3 mb-3">26</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"> 76.12% </span>
                                <span class="text-nowrap">In last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-graph-up card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">Traffic</h5>
                            <h3 class="mt-3 mb-3">~ 14 P/S</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-danger me-2"> -12.57% </span>
                                <span class="text-nowrap">Since last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class="bi bi-incognito card-icon"></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0">VPN Clients</h5>
                            <h3 class="mt-3 mb-3">8</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2"> 12.31% </span>
                                <span class="text-nowrap">Active right now</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7 col-lg-7">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Registered routers</h4>
                </div>

                <div class="card-body pt-0">
                    <table class="table table-responsive align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:10%">#ID</th>
                                <th>IP Address</th>
                                <th>MAC Address</th>
                                <th style="width:15%">Free Memory</th>
                                <th style="width:13%">Free Space</th>
                                <th class="text-center" style="width:10%">Active</th>
                                <th class="text-center" style="width:15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="routers.length == 0">
                                <td colspan="7" class="text-center" style="height:55px!important;">There are no routers registered in the system.</td>
                            </tr>
                            <tr v-for="router in routers" :key="router.id">
                                <td class="align-middle" style="height:55px!important;">#{{ router.id }}</td>
                                <td>{{ router.ip_address }}</td>
                                <td>{{ router.mac_address }}</td>
                                <td v-if="!router.disabled">{{ (router.freeMemory / 1000000).toFixed(2) }} MB</td>
                                <td v-else> - </td>
                                <td v-if="!router.disabled">{{ (router.freeSpace / 1000000).toFixed(2) }} MB</td>
                                <td v-else> - </td>
                                <td v-if="router.disabled" class="text-center">
                                    <span class="badge badge-danger-lighten">Disabled</span>
                                </td>
                                <td v-else class="text-center">
                                    <span class="badge badge-success-lighten">Active</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-xs btn-light table-button" title="Backup" :disabled="router.disabled" @click="backup(router)">
                                            <i class="bi bi-database-add"></i>
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
</template>

<style scoped>
.widget-flat {
    position: relative;
    overflow: hidden;
}

.card {
    border: none;
    margin-bottom: 24px;
    box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.15) !important;
    border-radius: 3px;
}

.card-header {
    margin-top: 0;
    background-color: #fff;
    border: 0;
    margin-bottom: 0;
    padding: 1.5rem;
}

.card-header .header-title {
    text-transform: uppercase;
    letter-spacing: 0.02em;
    font-size: 0.9rem;
    margin-top: 0;
}

.card-body {
    padding: 1.5rem;
}

.card-icon {
    color: #727cf5;
    font-size: 16px;
    background-color: rgba(114, 124, 245, 0.25);
    height: 40px;
    width: 40px;
    text-align: center;
    line-height: 40px;
    border-radius: 3px;
    display: inline-block;
}

.text-muted {
    color: #8a969c !important;
}

.mt-3 {
    margin-top: 1.5rem !important;
}

.mb-3 {
    margin-bottom: 1.5rem !important;
}

.me-2 {
    margin-right: 0.75rem !important;
}

.text-success {
    color: rgb(10, 207, 151) !important;
}

.text-danger {
    color: rgb(250, 92, 124) !important;
}

#arrow-icons {
    font-size: 14.4px;
    margin: 0;
}

.bi {
    margin: 0 !important;
}

.text-nowrap {
    font-size: 14.4px;
    color: rgb(138, 150, 156);
}
</style>
