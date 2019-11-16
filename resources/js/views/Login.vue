<template>
    <section class="hero is-light is-fullheight-with-navbar">
        <div class="hero-body has-text-centered">
            <div class="container">
                <div class="columns">
                    <div class="column is-offset-1-mobile is-10-mobile
                        is-offset-3-tablet is-6-tablet
                        is-offset-one-third-desktop is-one-third-desktop">

                        <h1 class="title">Log In</h1>

                        <b-notification
                            type="is-danger"
                            aria-close-label="Close warning"
                            role="alert"
                            :active="$route.params.hasOwnProperty('warning')"
                            has-icon>
                            <strong>Unauthorized access!</strong><br />Please login to view the {{ $route.params.warning }} page
                        </b-notification>

                        <validation-observer ref="observer" v-slot="{ invalid }" tag="form" @submit.prevent="submitLogin">
                            <b-input-with-validation
                                icon="at"
                                rules="required|email"
                                type="email"
                                name="email"
                                label="Email"
                                v-model="form.email"
                                :disabled="loading"
                                placeholder="your email address.."
                                />
                            <b-input-with-validation
                                icon="lock"
                                rules="required|min:6|alphanum"
                                type="password"
                                name="password"
                                label="Password"
                                :disabled="loading"
                                v-model="form.password"
                                placeholder="your password.."
                                />
                            <b-button type="is-primary"
                                    :loading="loading"
                                    :disabled="invalid"
                                    native-type="submit"
                                    icon-left="lock-open-outline"
                                    expanded>
                                <span>Sign In</span>
                            </b-button>
                        </validation-observer>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'Login',

    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            errors: {
                email: [],
                password: []
            },
            loading: false
        }
    },

    created() {
        if (this.$store.getters.isLoggedIn) {
            this.$router.push({ name: 'dashboard' })
        }
    },

    methods: {
        async submitLogin() {
            const isValid = await this.$refs.observer.validate()

            if (isValid) {
                this.loading = true
                const loggingIn = await this.$store.dispatch('login', this.form)

                if (loggingIn !== false) {
                    this.$buefy.toast.open({
                        message: 'Successfully logged in',
                        type: 'is-success',
                        duration: 2000
                    })
                    this.$refs.observer.reset()
                    this.$router.push({ name: 'dashboard', params: { arrive: 1 } })
                } else
                if (this.$store.state.errors) {
                    this.$refs.observer.setErrors(this.$store.state.errors)
                } else {
                    this.$buefy.toast.open({
                        message: 'Login failed, invalid email or password combination',
                        type: 'is-danger',
                        duration: 4000
                    })
                }
                this.loading = false
            }
        }
    }
}
</script>
