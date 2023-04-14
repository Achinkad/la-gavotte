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
            component: () => import('../views/IP/Firewall/Firewall.vue')
        },
        {
            path: '/ip/firewall/create',
            name: 'FirewallCreate',
            component: () => import('../views/IP/Firewall/FirewallCreate.vue')
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
            component: () => import('../views/Routing/BGP/BGP.vue')
        },
        {
            path: '/routing/bgp/create',
            name: 'BGPCreate',
            component: () => import('../views/Routing/BGP/BGPCreate.vue')
        },
        {
            path: '/routing/ospf/instance',
            name: 'OSPFInstance',
            component: () => import('../views/Routing/OSPF/OSPFInstance.vue')
        },
        {
            path: '/routing/ospf/instance/create',
            name: 'OSPFInstanceCreate',
            component: () => import('../views/Routing/OSPF/OSPFInstanceCreate.vue')
        },
        {
            path: '/routing/ospf/area',
            name: 'OSPFArea',
            component: () => import('../views/Routing/OSPF/OSPFArea.vue')
        },
        {
            path: '/routing/ospf/area/create',
            name: 'OSPFAreaCreate',
            component: () => import('../views/Routing/OSPF/OSPFAreaCreate.vue')
        },
        {
            path: '/routing/ospf/template',
            name: 'OSPFTemplate',
            component: () => import('../views/Routing/OSPF/OSPFTemplate.vue')
        },
        {
            path: '/routing/ospf/template/create',
            name: 'OSPFTemplateCreate',
            component: () => import('../views/Routing/OSPF/OSPFTemplateCreate.vue')
        },
        {
            path: '/routing/rip/instance',
            name: 'RIPInstance',
            component: () => import('../views/Routing/RIP/RIPInstance.vue')
        },
        {
            path: '/routing/rip/instance/create',
            name: 'RIPInstanceCreate',
            component: () => import('../views/Routing/RIP/RIPInstanceCreate.vue')
        },
        {
            path: '/routing/rip/template',
            name: 'RIPTemplate',
            component: () => import('../views/Routing/RIP/RIPTemplate.vue')
        },
        {
            path: '/routing/rip/template/create',
            name: 'RIPTemplateCreate',
            component: () => import('../views/Routing/RIP/RIPTemplateCreate.vue')
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
