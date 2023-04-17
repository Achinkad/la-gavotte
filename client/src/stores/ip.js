import { inject, ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRouterStore } from './router.js'

export const useIPStore = defineStore('ip', () => {
    const routerStore = useRouterStore()

    const axiosApi = inject('axiosApi') // Axios
    const notyf = inject('notyf') // Notyf

    const addresses = ref([]) // IP Addresses
    const routes = ref([]) // Routes
    const dhcpServers = ref([]) // DHCP Servers
    const dnsServers = ref([]) // DNS Servers

    // --- IP Addresses --- //
    async function loadAddresses(body) {
        await axiosApi.get('ip/address', { params: body }).then(response => {
            addresses.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function createAddress(body) {
        await axiosApi.post('ip/address/create', body).then(response => {
            notyf.success('The IP address was added with success.')
            addresses.value.push(response.data)
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editAddress(body) {
        await axiosApi.patch('ip/address/edit/' + body.get('id'), body).then(response => {
            notyf.success('The IP address was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteAddress(address, router) {
        let data = { routerID: router }

        await axiosApi.delete('ip/address/delete/' + address['.id'], { params: data }).then(response => {
            notyf.success('The IP Address was deleted with success.')

            // Remove from the array of addresses
            let index = addresses.value.indexOf(address)
            if (index > -1) addresses.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getAddresses = (() => { return addresses.value })

    // --- Routes --- //
    async function loadRoutes(body) {
        await axiosApi.get('ip/routes', { params: body }).then(response => {
            routes.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function createRoute(body) {
        await axiosApi.post('ip/route/create', body).then(response => {
            notyf.success('The route was added with success.')
            routes.value.push(response.data)
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editRoute(body) {
        await axiosApi.patch('ip/route/edit/' + body.get('id'), body).then(response => {
            notyf.success('The route was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteRoute(route, router) {
        let data = { routerID: router }

        await axiosApi.delete('ip/route/delete/' + route['.id'], { params: data }).then(response => {
            notyf.success('The route was deleted with success.')

            // Remove from the array of addresses
            let index = routes.value.indexOf(route)
            if (index > -1) routes.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getRoutes = (() => { return routes.value })

    // --- DHCP --- //
    async function loadDHCP(body) {
        await axiosApi.get('ip/dhcp-server', { params: body }).then(response => {
            dhcpServers.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function createDHCP(body) {
        await axiosApi.post('ip/dhcp-server/create', body).then(response => {
            notyf.success('The DHCP server was added with success.')
            dhcpServers.value.push(response.data)
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editDHCP(body) {
        await axiosApi.patch('ip/dhcp-server/edit/' + body.get('id'), body).then(response => {
            notyf.success('The DHCP server was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function deleteDHCP(dhcpServer, router) {
        let data = { routerID: router }

        await axiosApi.delete('ip/dhcp-server/delete/' + dhcpServer['.id'], { params: data }).then(response => {
            notyf.success('The DHCP server was deleted with success.')

            // Remove from the array of addresses
            let index = dhcpServers.value.indexOf(dhcpServer)
            if (index > -1) dhcpServers.value.splice(index, 1)

        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getDHCP = (() => { return dhcpServers.value })

    // --- DNS --- //
    async function loadDNS(body) {
        await axiosApi.get('ip/dns', { params: body }).then(response => {
            dnsServers.value = response.data
        }).catch(error => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    async function editDNS(body) {
        await axiosApi.patch('ip/dns/edit', body).then(response => {
            notyf.success('The DNS server was edited with success.')
        }).catch((error) => {
            notyf.error(error.response.data + " (" + error.response.status + ")")
        })
    }

    const getDNS = (() => { return dnsServers.value })

    return {
        getAddresses,
        loadAddresses,
        createAddress,
        deleteAddress,
        editAddress,
        loadRoutes,
        getRoutes,
        deleteRoute,
        createRoute,
        editRoute,
        loadDHCP,
        getDHCP,
        deleteDHCP,
        createDHCP,
        editDHCP,
        loadDNS,
        getDNS,
        editDNS
    }
})
