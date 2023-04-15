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

    async function createServerVPN(body) {
        await axiosApi.post('vpn/server/create', body).then(response => {
            notyf.success('The VPN server was added with success.')
            serversVPN.value.push(response.data)
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editServerVPN(body) {
        await axiosApi.patch('vpn/server/edit/' + body.get('id'), body).then(response => {
            notyf.success('The VPN address was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteServerVPN(vpnServer, router) {
        let data = { routerID: router }

        await axiosApi.delete('vpn/server/delete/' + vpnServer['.id'], { params: data }).then(response => {
            notyf.success('The VPN server was deleted with success.')

            // Remove from the array
            let index = serversVPN.value.indexOf(vpnServer)
            if (index > -1) serversVPN.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getServerVPN = (() => { return serversVPN.value })

    // --- VPN Clients --- //
    async function loadClientsVPN(body) {
        await axiosApi.get('vpn/client', { params: body }).then(response => {
            clientsVPN.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editServerVPN(body) {
        await axiosApi.patch('vpn/server/edit/' + body.get('id'), body).then(response => {
            notyf.success('The VPN address was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteServerVPN(vpnServer, router) {
        let data = { routerID: router }

        await axiosApi.delete('vpn/server/delete/' + vpnServer['.id'], { params: data }).then(response => {
            notyf.success('The VPN server was deleted with success.')

            // Remove from the array
            let index = serversVPN.value.indexOf(vpnServer)
            if (index > -1) serversVPN.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getServerVPN = (() => { return clientsVPN.value })

    return {
        loadServersVPN,
        getServerVPN,
        createServerVPN,
        editServerVPN,
        deleteServerVPN,
        loadClientsVPN
    }
})
