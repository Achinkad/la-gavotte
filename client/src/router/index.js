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
            path: '/bridges',
            name: 'Bridges',
            component: () => import('../views/Bridge/Bridge.vue')
        },
        {
            path: '/ports',
            name: 'Ports',
            component: () => import('../views/Bridge/Port.vue')
        },
        {
            path: '/bridge/create',
            name: 'BridgeCreate',
            component: () => import('../views/Bridge/BridgeCreate.vue')
        },
        {
            path: '/port/create',
            name: 'PortCreate',
            component: () => import('../views/Bridge/PortCreate.vue')
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
            path: '/wireless/networks',
            name: 'Networks',
            component: () => import('../views/Wireless/Networks.vue')
        },
        {
            path: '/wireless/security-profiles',
            name: 'SecurityProfiles',
            component: () => import('../views/Wireless/SecurityProfiles.vue')
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
            path: '/routers',
            name: 'Routers',
            component: () => import('../views/Routers.vue')
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
