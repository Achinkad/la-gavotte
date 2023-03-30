import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap'

import './assets/css/master.css'

const app = createApp(App)
const apiUrl = import.meta.env.VITE_API_URL
const routerUrl = import.meta.env.VITE_ROUTER_IP

/* --- AXIOS --- */
app.provide(
    'axiosApi',
    axios.create({
        baseURL: apiUrl + '/api',
        headers: {
            'Content-type': 'application/json',
            'Content-Encoding': 'gzip',
        },
    })
)
app.provide('apiUrl', apiUrl)

app.provide(
    'axiosRouter',
    axios.create({
        baseURL: routerUrl + '/rest',
        headers: {
            'Content-type': 'application/json',
            'Content-Encoding': 'gzip',
        },
    })
)
app.provide('routerUrl', routerUrl)

app.use(createPinia())
app.use(router)

app.mount('#app')
