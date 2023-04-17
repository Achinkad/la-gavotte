<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useBridgeStore } from "../../stores/bridge.js"
import { useRouterStore } from "../../stores/router.js"

const routerStore = useRouterStore()
const bridgeStore = useBridgeStore()
const axiosApi = inject('axiosApi')



const loadRouters = (() => { routerStore.loadRouters() })
const routers = computed(() => { return routerStore.getRouters() })

const routerIdentification = ref("-")

const bridge = ref({
    name: null,
    mtu: 1500,
    protocol: 'rstp'
})

const createBridge = () => {
    let formData = new FormData()

    formData.append('name', bridge.value.name)
    formData.append('mtu', bridge.value.mtu)
    formData.append('protocol', bridge.value.protocol)
    formData.append('identity', routerIdentification.value)


    bridgeStore.createBridges(formData)

}

onBeforeMount(() => {

    loadRouters()
})

</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <h2 class="p-title">Create Bridge</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <div class="px-1">
                            <router-link :to="{ name: 'Bridges'}"
                                                    :title="`Bridges`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">


                           <form class="row g-3" @submit.prevent="createBridge">
                            <div class="col-12">
                            <label>Select Router</label>
                                <select class="form-select" v-model="routerIdentification">
                                    <option value="-" selected hidden disabled>Select a router</option>
                                    <option v-for="router in routers" :key="router.id" :value="router.id" :disabled="router.disabled">{{ router.ip_address }}</option>
                                </select>
                            </div>
                            <div v-if="routerIdentification!='-'">
                                <div class="col-6">
                                    <label for="exampleInputEmail1">Name </label>
                                    <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Name of the bridge interface" v-model="bridge.name">
                                </div>
                                <div class="col-6">
                                    <label for="exampleInputPassword1">MTU [68-65535]</label>
                                    <input type="text" class="form-control" placeholder="Password" 
                                    pattern="^([6][8-9]|[7-9]\d{1}|[1-9]\d{2}|[1-9]\d{3}|[1-5]\d{4}|6[0-4]\d{3}|65[1-4]\d{2}|655[0-2][0-9]|6553[0-5])$"
                                    v-model="bridge.mtu">
                                </div>
                                <div class="col-12">
                                    <p>Select the protocol</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="none" v-model="bridge.protocol">
                                        <label class="form-check-label" for="none">None</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="stp" v-model="bridge.protocol">
                                        <label class="form-check-label" for="stp">STP</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="rstp" v-model="bridge.protocol">
                                        <label class="form-check-label" for="rstp">RSTP</label><br>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="protocol" value="mstp" v-model="bridge.protocol">
                                        <label class="form-check-label" for="rstp">MSTP</label><br>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
