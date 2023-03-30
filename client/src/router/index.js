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
            path: '/interfaces',
            name: 'Interfaces',
            component: () => import('../views/Interfaces.vue')
        },
        {
            path: '/bridge',
            name: 'Bridge',
            component: () => import('../views/Bridge.vue')
        },
        {
            path: '/ip/addresses',
            name: 'Addresses',
            component: () => import('../views/IP/Addresses.vue')
        },
        {
            path: '/ip/routes',
            name: 'Routes',
            component: () => import('../views/IP/Routes.vue')
        },
        {
            path: '/ip/dhcp',
            name: 'DHCP',
            component: () => import('../views/IP/DHCP.vue')
        },
        {
            path: '/ip/firewall',
            name: 'Firewall',
            component: () => import('../views/IP/Firewall.vue')
        },
        {
            path: '/wireless',
            name: 'Wireless',
            component: () => import('../views/Wireless.vue')
        },
        {
            path: '/routing/bgp',
            name: 'BGP',
            component: () => import('../views/Routing/BGP.vue')
        },
        {
            path: '/routing/ospf',
            name: 'OSPF',
            component: () => import('../views/Routing/OSPF.vue')
        },
        {
            path: '/routing/rip',
            name: 'RIP',
            component: () => import('../views/Routing/RIP.vue')
        },
        {
            path: '/vpn',
            name: 'VPN',
            component: () => import('../views/VPN.vue')
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
})

export default router
