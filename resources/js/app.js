import Vue from 'vue'
import Buefy from 'buefy'
import App from './views/App'
import store from './store'
import router from './router'
import http from './http'
import './validate'

Vue.use(Buefy, {
    defaultToastDuration: 3000,
    defaultNotificationDuration: 3000,
    defaultFieldLabelPosition: 'on-border'
})
Vue.config.productionTip = process.env.NODE_ENV === 'production'

Vue.prototype.$http = http

new Vue({
    ...App,
    router,
    store
})
