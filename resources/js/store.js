import Vue from 'vue'
import Vuex from 'vuex'
import Cookies from 'js-cookie'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        token: Cookies.get('token') || null,
        user: null,
        errors: []
    },
    mutations: {
        token(state, token) {
            state.token = token
            Cookies.set('token', token, { expires: 1 })
        },
        logout(state) {
            state.token = null
            state.user = null
            Cookies.remove('token')
        },
        setUser(state, userData) {
            state.user = state.token ? userData : null
        },
        setErrors(state, errors) {
            state.errors = errors || []
        }
    },

    getters: {
        isLoggedIn: state => !!state.token
    },

    actions: {
        async login({
            commit
        }, user) {
            commit('setErrors')

            try {
                const { data } = await this._vm.$http.post('login', user)

                if (data.status && data.status === true && data.token) {
                    commit('token', data.token)
                    await this._vm.$http.get('user', {
                        headers: {
                            Authorization: 'Bearer ' + data.token
                        }
                    }).then(response => {
                        commit('setUser', response.data)
                    })
                    return true

                } else if (data.message) {
                    this._vm.$buefy.toast.open({
                        message: data.message,
                        type: 'is-danger',
                        duration: 5000,
                        queue: false
                    })
                } else {
                    commit('setErrors', data)
                }

            } catch (error) {
                console.log('login', error)
                commit('logout')
            }

            return false
        },
        async logout({
            commit
        }) {
            const { data } = await this._vm.$http.get('logout')
            if (data.status) {
                commit('logout')
            }
        }
    }
})

export default store
