import { inject, ref } from 'vue'
import { defineStore } from 'pinia'

export const useRouterStore = defineStore('router', () => {
    const axiosApi = inject('axiosApi') // Axios

    const routers = ref([]) // Routers

    async function registerRouter(data) {
        await axiosApi.post('routers', data).then((response) => {
            routers.value.push(response.data.data)
            return true
        }).catch((error) => {
            return false
        })
    }

    async function loadRouters(dashboard) {
        if (dashboard) {
            await axiosApi.get('routers', { params:{ dashboard: true }}).then((response) => {
                routers.value = response.data
            })
        } else {
            await axiosApi.get('routers').then((response) => {
                routers.value = response.data
            })
        }
    }

    function getRouterById(id) {
        return routers.value.filter(router => router.id == id)
    }

    const getRouters = (() => {
        return routers.value
    })

    return {
        registerRouter,
        getRouters,
        getRouterById,
        loadRouters,
    }
})
