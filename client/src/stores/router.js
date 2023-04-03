import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useRouterStore = defineStore('router', () => {
    const axiosApi = inject('axiosApi')

    const router = ref([])

    const registerRouter = ((data) => {
        axiosApi.post('routers', data).then((response) => {
            router.value = response.data
            return true
        }).catch((error) => {
            return false
        })
    })

    const getRouters = (() => {
        axiosApi.get('routers').then((response) => {
            router.value = response.data
        })
    })

    return { registerRouter, getRouters }
})
