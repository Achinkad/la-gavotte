<script setup>
import { ref, inject,toRef } from 'vue'

import { useWirelessStore } from '../../stores/wireless.js'

const notyf = inject('notyf') // Notyf

const wirelessStore = useWirelessStore() // Wireless Pinia Store

const props = defineProps({ router: { type: Number } })

const routerIdentification = toRef(props, 'router') // Router IP Reference

const securityProfile = ref({
    name: null,
    mode: 'none',
    authenticationTypes: null,
    wpaPreSharedKey: null,
    wpa2PreSharedKey: null
})

const hasError = ref(false)

const createSecurityProfile = (() => {
    let formData = new FormData()

    formData.append('router', props.router)
    formData.append('name', securityProfile.value.name)
    formData.append('mode', securityProfile.value.mode)

    if(securityProfile.value.authenticationTypes!=null){
        formData.append('authenticationTypes', securityProfile.value.authenticationTypes)
    }

    if(securityProfile.value.wpaPreSharedKey!=null){
        formData.append('wpaPreSharedKey', securityProfile.value.wpaPreSharedKey)
    }

    if(securityProfile.value.wpa2PreSharedKey!=null){
        formData.append('wpa2PreSharedKey', securityProfile.value.wpa2PreSharedKey)
    }

    let key = securityProfile.value.wpaPreSharedKey
    let key2 = securityProfile.value.wpa2PreSharedKey

    if ((key && (key.length < 8 || key.length > 64)) || (key2 && (key2.length < 8 || key2.length > 64))) {
        hasError.value = true
        notyf.error("WPA Keys must have between 8 and 64 characters.")
    } else {
        hasError.value = false
        wirelessStore.createSecurityProfile(formData)
        Object.entries(securityProfile.value).forEach(([i]) => { securityProfile.value[i] = null })
    }
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Create a new security profile</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" @submit.prevent="createSecurityProfile">
                <div class="col-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a Name"
                    v-model="securityProfile.name" required>
                </div>
                <div class="col-6">
                    <label for="mode" class="form-label">Mode <span class="text-danger">*</span></label>
                    <select class="form-select" id="mode" v-model='securityProfile.mode' required>
                        <option value="none">none</option>
                        <option value="dynamic-keys">dynamic keys</option>
                        <option value="static-keys-optional">static keys optional</option>
                        <option value="static-keys-required">static keys required</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="authType" class="form-label">Authentication Types</label>
                    <select class="form-select" id="mode" v-model='securityProfile.authenticationTypes' :disabled="securityProfile.mode != 'dynamic-keys'">
                        <option value="null" selected hidden disabled>Select a Authentication Type</option>
                        <option value="wpa-psk">wpa-psk</option>
                        <option value="wpa2-psk">wpa2-psk</option>
                        <option value="wpa-eap">wpa-eap</option>
                        <option value="wpa2-eap">wpa2-eap</option>
                    </select>
                </div>
                <div class="col-6" v-if="securityProfile.authenticationTypes == 'wpa-psk' && securityProfile.mode == 'dynamic-keys'">
                    <label for="wpaKey" class="form-label">WPA Pre-Shared Key <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" :class="{ hasErrorForm: hasError }" id="wpaKey"
                    placeholder="Enter a WPA Key" v-model="securityProfile.wpaPreSharedKey">
                    <div id="wpaKeyHelp" class="form-text" :class="{ 'text-danger': hasError }">Must be 8-64 characters long.</div>
                </div>
                <div class="col-6" v-if="securityProfile.authenticationTypes == 'wpa2-psk' && securityProfile.mode == 'dynamic-keys'">
                    <label for="wpa2Key" class="form-label">WPA2 Pre-Shared Key <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" :class="{ hasErrorForm: hasError }" id="wpa2Key"
                    placeholder="Enter a WPA2 Key" v-model="securityProfile.wpa2PreSharedKey">
                    <div id="wpa2KeyHelp" class="form-text" :class="{ 'text-danger': hasError }">Must be 8-64 characters long.</div>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end" v-if="isNaN(routerIdentification)"><u>Note: You must select a router</u>&nbspto add a new Security Profile.</div>
                <div class="col-12 mt-4 d-flex justify-content-end" v-else>
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Add Security Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
