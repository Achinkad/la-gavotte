<script setup>
import { ref, inject } from 'vue'
import { useRouterStore } from '../stores/router.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()

const router = ref({
    ip: null,
    username: null,
    password: null
})

const registerRouter = () => {
    let formData = new FormData()

    formData.append('ip', router.value.ip)
    formData.append('username', router.value.username)
    formData.append('password', router.value.password)

    axiosApi.post('routers/login', formData).then((response) => {
        routerStore.saveAuthToken(response.data.token)
        notyf.success('A new connection with a router has been established.')
    }).catch((error) => {
        notyf.error('Oops, an error has occurred.')
    })
}

const showInterfaces = () => {
    axiosApi.get('routers/interfaces').then((response) => {
        console.log(response.data)
    })
}
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box mt-4">
                <div>
                    <h2 class="p-title">Routers</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
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
                                        <th>#ID</th>
                                        <th>Router SSID</th>
                                        <th>IP Address</th>
                                        <th>MAC Address</th>
                                        <th class="text-center" style="width: 20%">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">#1</td>
                                        <td>BERTOLO</td>
                                        <td>192.168.10.10</td>
                                        <td>C4:AD:34:9F:52:8A</td>
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
            <div class="col-12">
                <div class="card card-h-100">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Show interfaces</h4>
                    </div>
                    <div class="card-body pt-0">
                        <form class="row g-3 needs-validation" novalidate @submit.prevent="showInterfaces">
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
                                    <button type="submit" class="btn btn-primary">Show interfaces</button>
                                </div>
                            </div>
                        </form>
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
                <form class="row g-3 needs-validation" novalidate @submit.prevent="registerRouter">
                    <div class="col-12">
                        <label for="ip" class="form-label">IP Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ip" placeholder="Enter an IP Address"
                        pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]).){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$"
                        v-model="router.ip" required>
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
