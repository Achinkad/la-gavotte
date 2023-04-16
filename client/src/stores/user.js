import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', () => {
    const axiosApi = inject('axiosApi')
    const notyf = inject('notyf') 
    const user = ref(null)

    const userId = computed(() => {
        return user.value?.id ?? -1
    })

    async function load_user() {
        try {
            const response = await axiosApi.get('user')
            user.value = response.data.data
        } catch (error) {
            clear_user()
            throw error
        }
    }

    function clear_user() {
        delete axiosApi.defaults.headers.common.Authorization
        sessionStorage.removeItem('token')
        user.value = null
    }

    async function login(credentials) {
        try {
            const response = await axiosApi.post('login', credentials)
            axiosApi.defaults.headers.common.Authorization = "Bearer " + response.data.access_token
            sessionStorage.setItem('token', response.data.access_token)
            await load_user()
            notyf.success('Logged in with success.')
            return true
        }
        catch(error) {
            clear_user()
            notyf.error("Invalid username or password")
            return false
        }
    }

    async function logout() {
        try {
            await axiosApi.post('logout')
            clear_user()
            notyf.success('Logged out with success.')
            return true
        } catch (error) {
            notyf.success('Something went wrong while logging out!')
            return false
        }
    }

    async function restoreToken () {
        let storedToken = sessionStorage.getItem('token')
        if (storedToken) {
            axiosApi.defaults.headers.common.Authorization = "Bearer " + storedToken
            await load_user()
            return true
        }
        clear_user()
        return false
    }

    return {
        user,
        userId,
        login,
        logout,
        restoreToken
    }
})
