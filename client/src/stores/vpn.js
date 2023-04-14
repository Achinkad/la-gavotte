import { inject, ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRouterStore } from './router.js'

export const useVPNStore = defineStore('vpn', () => {
    const routerStore = useRouterStore()

    const axiosApi = inject('axiosApi') // Axios
    const notyf = inject('notyf') // Notyf

    const serversVPN = ref([]) // VPN Servers
    const clientsVPN = ref([]) // VPN Clients

    // --- VPN Servers --- //
    async function loadServersVPN(body) {
        await axiosApi.get('vpn/server', { params: body }).then(response => {
            serversVPN.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getServerVPN = (() => { return serversVPN.value })

    return {
        loadServersVPN,
        getServerVPN
    }
})
