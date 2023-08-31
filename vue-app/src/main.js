import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from "axios"
import {container} from "jenesius-vue-modal/dist/jenesius-vue-modal.cjs";

const axiosInstace = axios.create({
    baseURL: import.meta.env.VITE_LOCAL_SERVER_PATH || "",
    withCredentials: true
})

const app = createApp(App)
app.config.globalProperties.$axios = {...axiosInstace}
app.component("widget-container-modal", container)
app.use(router)

axiosInstace.get('/sanctum/csrf-cookie')
    .then(() => {
        app.mount('#app')
        // console.log("CRSRF Init")
    })

