import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import Welcome from './views/Welcome'
import Login from './views/Login'
import Register from './views/Register'
import Dashboard from './views/Dashboard'
import NotFound from './views/NotFound'

Vue.use(Router)

const routes = [
    {
        path: '/',
        name: 'home',
        component: Welcome
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true
        }
    },
    {
        path: '*',
        name: '404',
        component: NotFound
    }
];

const router = new Router({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.auth && to.meta.auth === true && !store.getters.isLoggedIn) {
        next({
            name: 'login',
            params: {
                warning: to.name
            }
        })
    } else {
        next()
    }
})

export default router
