import { inject } from 'vue'
import { defineStore } from 'pinia'

export const useRouterStore = defineStore('router', () => {
    const axiosRouter = inject('axiosRouter')

    const saveAuthToken = ((username, password) => {
        const authToken = Buffer.from(`${username}:${password}`, 'utf8').toString('base64')
        axiosApi.defaults.headers.common.Authorization = `Basic ${authToken}`
        sessionStorage.setItem('routerAuthToken', authToken)
    })

    return saveAuthToken
})
