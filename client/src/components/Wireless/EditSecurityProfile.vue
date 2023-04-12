<script setup>
import { ref, inject } from 'vue'

import { useWirelessStore } from '../../stores/wireless.js'

const notyf = inject('notyf') // Notyf

const wirelessStore = useWirelessStore() // Wireless Pinia Store

const props = defineProps({
    securityProfile: { type: Object }, // Security Profile
    router: { type: Number } // Router ID
})

const hasError = ref(false) // Flag for Error Handling
const passwordFieldType = ref('password') // Password Field Toogle

const showPassword = (() => {
    passwordFieldType.value = passwordFieldType.value === 'password' ? 'text' : 'password'
})

const editSecurityProfile = (() => {
    let formData = new FormData()

    formData.append('id', props.securityProfile['.id'])
    formData.append('router', props.router)
    formData.append('name', props.securityProfile.name)
    formData.append('mode', props.securityProfile.mode)
    formData.append('authenticationTypes', props.securityProfile['authentication-types'])
    formData.append('wpaPreSharedKey', props.securityProfile['wpa-pre-shared-key'])
    formData.append('wpa2PreSharedKey', props.securityProfile['wpa2-pre-shared-key'])

    let key = props.securityProfile['wpa-pre-shared-key']
    let key2 = props.securityProfile['wpa2-pre-shared-key']

    if ((key && (key.length < 8 || key.length > 64)) || (key2 && (key2.length < 8 || key2.length > 64))) {
        hasError.value = true
        notyf.error("WPA Keys must be 8-64 characters long.")
    } else {
        hasError.value = false
        wirelessStore.editSecurityProfile(formData)
    }
})
</script>

<template>
    <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">You're editing the security profile: {{ securityProfile.name }}</h4>
        </div>
        <div class="card-body pt-0">
            <form class="row g-3 needs-validation" novalidate @submit.prevent="editSecurityProfile">
                <div class="col-6">
                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" placeholder="Enter a Name"
                    v-model="securityProfile.name" required>
                </div>
                <div class="col-6">
                    <label for="mode" class="form-label">Mode <span class="text-danger">*</span></label>
                    <select class="form-select" id="mode" v-model='securityProfile.mode'>
                        <option value="null" selected hidden disabled>Select a Mode</option>
                        <option value="none">none</option>
                        <option value="dynamic-keys">dynamic keys</option>
                        <option value="static-keys-optional">static keys optional</option>
                        <option value="static-keys-required">static keys required</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="authType" class="form-label">Authentication Types</label>
                    <select class="form-select" id="mode" v-model='securityProfile["authentication-types"]' :disabled="securityProfile.mode != 'dynamic-keys'">
                        <option value="null" selected hidden disabled>Select a Authentication Type</option>
                        <option value="wpa-psk">wpa-psk</option>
                        <option value="wpa2-psk">wpa2-psk</option>
                        <option value="wpa-eap">wpa-eap</option>
                        <option value="wpa2-eap">wpa2-eap</option>
                    </select>
                </div>
                <div class="col-6" v-if="securityProfile['authentication-types'] == 'wpa-psk' && securityProfile.mode == 'dynamic-keys'">
                    <label for="wpaKey" class="form-label">WPA Pre-Shared Key <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input :type="passwordFieldType" class="form-control" :class="{ hasErrorForm: hasError }" id="wpaKey"
                        placeholder="Enter a WPA Key" v-model="securityProfile['wpa-pre-shared-key']">
                        <span class="input-group-text" id="wpaEye" @click="showPassword()">
                            <i class="bi" :class="{
                                'bi-eye': passwordFieldType == 'password',
                                'bi-eye-slash': passwordFieldType == 'text'
                                }"></i>
                        </span>
                    </div>
                    <div id="wpaKeyHelp" class="form-text" :class="{ 'text-danger': hasError }">Must be 8-64 characters long.</div>
                </div>
                <div class="col-6" v-if="securityProfile['authentication-types'] == 'wpa2-psk' && securityProfile.mode == 'dynamic-keys'">
                    <label for="wpa2Key" class="form-label">WPA2 Pre-Shared Key <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input :type="passwordFieldType" class="form-control" :class="{ hasErrorForm: hasError }" id="wpa2Key"
                        placeholder="Enter a WPA2 Key" v-model="securityProfile['wpa2-pre-shared-key']">
                        <span class="input-group-text" id="wpaEye" @click="showPassword()">
                            <i class="bi" :class="{
                                'bi-eye': passwordFieldType == 'password',
                                'bi-eye-slash': passwordFieldType == 'text'
                                }"></i>
                        </span>
                    </div>
                    <div id="wpa2KeyHelp" class="form-text" :class="{ 'text-danger': hasError }">Must be 8-64 characters long.</div>
                </div>
                <div class="col-12 mt-4 d-flex justify-content-end">
                    <div class="px-1">
                        <button type="reset" class="btn btn-light px-4 me-1">Clear</button>
                    </div>
                    <div class="px-1">
                        <button type="submit" class="btn btn-primary">Edit Security Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
