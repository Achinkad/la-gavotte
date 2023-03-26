import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';

import './assets/css/master.css'

const app = createApp(App)
const api_url = import.meta.env.VITE_API_URL;

/* --- AXIOS --- */
app.provide(
    'axios',
    axios.create({
        baseURL: api_url + '/api',
        headers: {
            'Content-type': 'application/json',
            'Content-Encoding': 'gzip',
        },
    })
);
app.provide("api_url", api_url);

app.use(createPinia())
app.use(router)

app.mount('#app')
