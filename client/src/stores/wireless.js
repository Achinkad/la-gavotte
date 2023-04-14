import { inject, ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRouterStore } from './router.js'

export const useWirelessStore = defineStore('wireless', () => {
    const routerStore = useRouterStore()

    const axiosApi = inject('axiosApi') // Axios
    const notyf = inject('notyf') // Notyf

    const wirelessNetworks = ref([]) // Wireless Networks
    const securityProfiles = ref([]) // Security Profiles

    // --- Wireless Networks --- //
    async function loadWirelessNetworks(body) {
        await axiosApi.get('wireless', { params: body }).then(response => {
            wirelessNetworks.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editWirelessNetwork(body) {
        await axiosApi.patch('wireless/' + body.get('id'), body).then(response => {
            notyf.success('The wireless network was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getWirelessNetworks = (() => { return wirelessNetworks.value })

    function getWirelessNetworkById(id) {
        return wirelessNetworks.value.filter( w => w['.id'] == '*' + id)
    }

    // --- Security Profiles --- //
    async function loadSecurityProfiles(body) {
        await axiosApi.get('wireless/security-profiles', { params: body }).then(response => {
            securityProfiles.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function createSecurityProfile(body) {
        await axiosApi.post('wireless/create/security-profile', body).then(response => {
            notyf.success('The security profile was added with success.')
            securityProfiles.value.push(response.data)
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editSecurityProfile(body) {
        await axiosApi.put('wireless/edit/security-profile/' + body.get('id'), body).then(response => {
            notyf.success('The security profile was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteSecurityProfile(securityProfile, router) {
        let data = { routerID: router }

        await axiosApi.delete('wireless/delete/security-profile/' + securityProfile['.id'], { params: data }).then(response => {
            notyf.success('The security profile was deleted with success.')

            // Remove from the array of security profiles
            let index = securityProfiles.value.indexOf(securityProfile)
            if (index > -1) securityProfiles.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }


    const getSecurityProfiles = (() => { return securityProfiles.value })

    return {
        getWirelessNetworks,
        loadWirelessNetworks,
        getWirelessNetworkById,
        getSecurityProfiles,
        loadSecurityProfiles,
        editWirelessNetwork,
        createSecurityProfile,
        editSecurityProfile,
        deleteSecurityProfile
    }
})
