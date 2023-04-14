import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

import { Notyf } from 'notyf'
import 'notyf/notyf.min.css'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'bootstrap'

import './assets/css/master.css'

const app = createApp(App)
const apiUrl = import.meta.env.VITE_API_URL

/* --- AXIOS --- */
app.provide(
    'axiosApi',
    axios.create({
        baseURL: apiUrl + '/api',
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Content-type': 'application/json',
            'Content-Encoding': 'gzip',
        },
    })
)
app.provide('apiUrl', apiUrl)

app.provide('notyf', new Notyf({
    duration: 4000,
    types : [
        {
            type: 'success',
            icon: false,
            dismissible: true
        },
        {
            type: 'error',
            icon: false,
            dismissible: true
        },
        {
            type: 'info',
            background: '#727cf5',
            icon: false,
            dismissible: true
        }
    ]
}))

app.use(createPinia())
app.use(router)

app.mount('#app')
