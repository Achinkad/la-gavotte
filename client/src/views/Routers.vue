<script setup>
import { ref, inject, computed, onBeforeMount } from 'vue'
import { useRouterStore } from '../stores/router.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()

const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const router = ref({
    ip_address: null,
    username: null,
    password: null
})

const registerRouter = () => {
    let formData = new FormData()

    formData.append('ip_address', router.value.ip_address)
    formData.append('authorization', btoa(router.value.username + ':' + router.value.password))

    if (routerStore.registerRouter(formData)) {
        loadRouters()
        notyf.success('A new router has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }
}

// Get All Routers
onBeforeMount(() => {
    loadRouters()
})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Routers</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Connected Routers</h4>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-responsive align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:15%">#ID</th>
                                        <th>Identity</th>
                                        <th>IP Address</th>
                                        <th>MAC Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="routers.length == 0">
                                        <td colspan="5" class="text-center" style="height:55px!important;">There are no routers registered in the system.</td>
                                    </tr>
                                    <tr v-for="router in routers" :key="router.id">
                                        <td class="align-middle" style="height:55px!important;">#{{ router.id }}</td>
                                        <td>{{ router.identity }}</td>
                                        <td>{{ router.ip_address }}</td>
                                        <td>{{ router.mac_address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Connect with a new router</h4>
                </div>
                <div class="card-body pt-0">
                    <form class="row g-3 needs-validation" @submit.prevent="registerRouter">
                        <div class="col-12">
                            <label for="ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ip_address" placeholder="Enter an IP Address"
                            pattern="^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                            v-model="router.ip_address" required>
                        </div>
                        <div class="col-6">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" placeholder="Enter username"
                            v-model="router.username" required>
                        </div>
                        <div class="col-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password"
                            placeholder="Enter password" v-model="router.password" required>
                        </div>
                        <div class="col-12 mt-4 d-flex justify-content-end">
                            <div class="px-1">
                                <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                            </div>
                            <div class="px-1">
                                <button type="submit" class="btn btn-primary">Add Router</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
