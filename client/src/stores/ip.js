import { inject, ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRouterStore } from './router.js'

export const useIPStore = defineStore('ip', () => {
    const routerStore = useRouterStore()

    const axiosApi = inject('axiosApi') // Axios
    const notyf = inject('notyf') // Notyf

    const addresses = ref([]) // IP Addresses

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

    return {
        getAddresses,
        loadAddresses,
        createAddress,
        deleteAddress,
        editAddress
    }
})
