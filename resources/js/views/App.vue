<template>
    <div id="page">
        <b-loading :is-full-page="isFullPage" :active.sync="isLoading" :can-cancel="false"></b-loading>
        <b-navbar :fixed-top="true" :shadow="true" type="primary">
            <template slot="brand">
                <b-navbar-item tag="router-link" :to="{ name: 'home' }">
                    <h1><strong>SimpleAuth</strong></h1>
                </b-navbar-item>
            </template>
            <template slot="start">
                <b-navbar-item href="/api/documentation">Documentation</b-navbar-item>
            </template>
            <template slot="end">
                <b-navbar-item tag="div">
                    <div class="buttons" v-if="user && user.email">
                        <b-dropdown position="is-bottom-left" aria-role="menu">
                            <button class="button is-primary" slot="trigger">
                                <span>Hi, {{ user.firstname }}!</span>
                                <b-icon icon="menu-down"></b-icon>
                            </button>
                            <b-dropdown-item has-link aria-role="menuitem">
                                <router-link :to="{name:'dashboard'}">
                                    <b-icon icon="account-circle-outline"></b-icon>&nbsp;&nbsp;Dashboard
                                </router-link>
                            </b-dropdown-item>
                            <b-dropdown-item aria-role="listitem" @click="logout">
                                <b-icon icon="logout"></b-icon>&nbsp;&nbsp;Log Out
                            </b-dropdown-item>
                        </b-dropdown>
                    </div>
                    <div class="buttons" v-else>
                        <router-link class="button is-primary" :to="{name:'register'}"><strong>Sign up</strong></router-link>
                        <router-link class="button is-light" :to="{name:'login'}">Log in</router-link>
                    </div>
                </b-navbar-item>
            </template>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    el: '#app',
    name: 'App',

    computed: mapState(['user']),

    data() {
        return {
            isFullPage: true,
            isLoading: false
        }
    },

    created() {
        if (this.$store.getters.isLoggedIn) {
            this.checkAuth()
        }
    },

    methods: {
        async checkAuth() {
            await this.$http.get('user').then(response => {
                if (response.data.email) {
                    this.$store.commit('setUser', response.data)
                } else {
                    this.$buefy.toast.open({
                        message: 'Your session has expired, please login again.',
                        type: 'is-warning',
                        duration: 1500,
                        position: 'is-bottom'
                    })
                    this.$store.commit('logout')
                    this.$router.push({ name: 'login' })
                }
            })
        },

        logout() {
            this.$buefy.dialog.confirm({
                message: 'Are you sure want to log out from application?',
                confirmText: 'Yes!',
                onConfirm: this.doLogout
            })
        },

        async doLogout() {
            await this.$store.dispatch('logout')
            this.$buefy.toast.open({
                message: 'Successfully logged out',
                type: 'is-success'
            })
            this.$router.push({ name: 'home' })
        }
    }
}
</script>
