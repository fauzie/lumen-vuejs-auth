<template>
    <section class="hero is-light is-fullheight-with-navbar">
        <div class="hero-body has-text-centered">
            <div class="container">
                <div class="columns">
                    <div class="column has-background-white is-offset-1-mobile is-10-mobile
                            is-offset-3-tablet is-6-tablet
                            is-offset-one-third-desktop is-one-third-desktop">
                        <h1 class="title">Sign Up</h1>

                        <b-notification
                            type="is-success"
                            role="alert"
                            :active="isSuccess"
                            has-icon>
                            <strong>Registration Success!</strong><br />To continue please login with your email and password
                        </b-notification>

                        <b-collapse :open="!isSuccess" aria-id="registrationForm">
                            <validation-observer
                                ref="observer"
                                v-slot="{ invalid }"
                                @submit.prevent="submitForm"
                                tag="form"
                                >
                                <b-input-with-validation
                                    icon="at"
                                    rules="required|email"
                                    type="email"
                                    name="email"
                                    label="Email"
                                    :disabled="loading"
                                    v-model="form.email"
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
                                        icon-left="account-plus"
                                        expanded>
                                    <span>Register</span>
                                </b-button>
                            </validation-observer>
                        </b-collapse>
                        <b-button type="is-primary"
                                :loading="loading"
                                icon-left="lock-open-outline"
                                v-bind:class="{ 'is-hidden': !isSuccess }"
                                @click="goLoginPage"
                                expanded>
                            <span>Log In</span>
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'Register',

    data() {
        return {
            loading: false,
            isSuccess: false,
            form: {
                phone: '',
                firstname: '',
                lastname: '',
                dob: '',
                gender: '',
                email: ''
            }
        }
    },

    methods: {
        async submitForm() {
            this.loading = true
            const isValid = await this.$refs.observer.validate()

            if (isValid) {
                setTimeout(() => {
                    this.isSuccess = true
                    this.loading = false
                }, 3000)
            }
        },
        goLoginPage() {
            this.$router.push({ name: 'login' })
        }
    }
}
</script>
