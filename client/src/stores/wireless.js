import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useWirelessStore = defineStore('wireless', () => {
    const axiosApi = inject('axiosApi') // Axios

    const wirelessNetworks = ref([]) // Wireless Networks
    const securityProfiles = ref([]) // Security Profiles

    // Wireless Networks
    async function loadWirelessNetworks(body) {
        await axiosApi.get('wireless', { params: body }).then((response) => {
            wirelessNetworks.value = response.data
        }).catch((error) => {
            console.log(error)
        })
    }

    const getWirelessNetworks = (() => { return wirelessNetworks.value })

    // Security Profiles
    async function loadSecurityProfiles(body) {
        await axiosApi.get('wireless/security-profiles', { params: body }).then((response) => {
            securityProfiles.value = response.data
        }).catch((error) => {
            console.log(error)
        })
    }

    const getSecurityProfiles = (() => { return securityProfiles.value })

    return {
        getWirelessNetworks,
        loadWirelessNetworks,
        getSecurityProfiles,
        loadSecurityProfiles
    }
})
