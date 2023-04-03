import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useRouterStore = defineStore('router', () => {
    // Axios
    const axiosApi = inject('axiosApi')

    // Array of routers
    const routers = ref([])

    const registerRouter = ((data) => {
        axiosApi.post('routers', data).then((response) => {
            routers.value = response.data.data
            return true
        }).catch((error) => {
            return false
        })
    })

    async function loadRouters() {
        await axiosApi.get('routers').then((response) => {
            routers.value = response.data.data
        })
    }

    const getRouters = (() => {
        return routers.value
    })

    const showInterfaces = () => {
        axiosApi.get('routers/interfaces').then((response) => {
            router.value = response.data
        })
    }

    return {
        registerRouter,
        getRouters,
        loadRouters
   , showInterfaces }
})
