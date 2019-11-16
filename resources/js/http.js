import axios from 'axios'
import store from './store'

const http = axios.create({
    baseURL: '/api',
    timeout: 3000,
    maxRedirects: 1,
    responseType: 'json',
    validateStatus: status => {
        return status < 500
    },
    headers: {
        common: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    }
})

// Add a request interceptor
http.interceptors.request.use(config => {
    if (store.getters.isLoggedIn) {
        config.headers['Authorization'] = 'Bearer ' + store.state.token
    }
    return config
})

export default http
