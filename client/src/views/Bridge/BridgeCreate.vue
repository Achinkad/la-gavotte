<script setup>
// Write your script here
import { inject,onBeforeMount,ref,computed,watch } from 'vue'
import { useBridgeStore } from "../../stores/bridge.js"


const bridgeStore = useBridgeStore()
const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

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
    console.log(bridge.value.protocol)

    if (bridgeStore.createBridges(formData)) {
        notyf.success('A new router has been added.')
    } else {
        notyf.error('Oops, an error has occurred.')
    }
}

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
                            <router-link :to="{ name: 'Bridge'}"
                                                    :title="`Bridge`">
                                <button class="btn btn-primary"><i class="bi bi-arrow-left"></i> Go Back</button>
                            </router-link>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                           <form class="row g-3" @submit.prevent="createBridge">
                                <div class="col-4">
                                    <label for="exampleInputEmail1">Name </label>
                                    <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Name of the bridge interface" v-model="bridge.name">
                                </div>
                                <div class="col-4">
                                    <label for="exampleInputPassword1">MTU</label>
                                    <input type="number" class="form-control" placeholder="Password" v-model="bridge.mtu">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
