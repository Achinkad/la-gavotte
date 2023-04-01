import { inject } from 'vue'
import { defineStore } from 'pinia'

export const useRouterStore = defineStore('router', () => {
    const axiosApi = inject('axiosApi')

    const saveAuthToken = ((authToken) => {
        axiosApi.defaults.headers.common.AuthorizationRouter = authToken
        sessionStorage.setItem('routerAuthToken', authToken)
    })

    const loadToken = (() => {
        let token = sessionStorage.getItem('routerAuthToken')

        if (token) {
            axiosApi.defaults.headers.common.AuthorizationRouter = token
            return true
        }
        
        clearToken()
        return false
    })

    const clearToken = (() => {
        delete axiosApi.defaults.headers.common.AuthorizationRouter
        sessionStorage.removeItem('routerAuthToken')
    })

    return { saveAuthToken, loadToken, clearToken }
})
