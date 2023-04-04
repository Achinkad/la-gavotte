<script setup>
import { ref, watch, computed, inject, onBeforeMount } from 'vue'
import { useRouterStore } from '../../stores/router.js'
import { useWirelessStore } from '../../stores/wireless.js'

const axiosApi = inject('axiosApi')
const notyf = inject('notyf')

const routerStore = useRouterStore()
const wirelessStore = useWirelessStore()

const props = defineProps({ id: { type: Number, required: true } })

onBeforeMount(() => {
    console.log(props.id)
})
</script>

<template>
    <div class="row">
        <div class="col-12">
            <div class="p-title-box">
                <div class="p-title-right" style="width:15%;">
                    <p>Button Here.</p>
                </div>
                <h2 class="p-title">Wireless Network</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Wireless networks available</h4>
                        </div>
                        <div class="card-body pt-0">
                            <p>Lorem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
