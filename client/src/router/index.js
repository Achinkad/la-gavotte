import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from "../stores/user.js";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'Dashboard',
            component: () => import('../views/Dashboard.vue')
        },
        {
            path: '/login',
            name: 'Login',
            component: () => import('../views/Login.vue')
        },
        {
            path: '/register',
            name: 'Register',
            component: () => import('../views/Register.vue')
        }
    ]
})

let handlingFirstRoute = true

router.beforeEach(async (to, from, next) => {
    const userStore = useUserStore()

    if (handlingFirstRoute) {
        handlingFirstRoute = false
        await userStore.restoreToken()
    }

    if (to.name == "Login" || to.name == "Register") {
        next()
        return
    } else {
        if (!userStore.user) {
            next({ name: "Login" })
            return
        }
        next()
    }

    next()
})

export default router
