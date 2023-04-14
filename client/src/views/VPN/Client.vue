<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'

import { useRouterStore } from '../../stores/router.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()

const routerIdentification = ref('-') // Current Router

// Routers
const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

// Load Routers
onBeforeMount(() => {
    loadRouters()
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
                <h2 class="p-title">VPN Client</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Title here</h4>
                        </div>
                        <div class="card-body pt-0">
                            <p>Lorem ipsum.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
