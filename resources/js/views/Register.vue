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
                                aria-autocomplete="disabled"
                                autocomplete="disabled"
                                >
                                <b-input-with-validation
                                    icon="account-arrow-left"
                                    rules="required|alphanumspace|min:3"
                                    type="text"
                                    name="firstname"
                                    label="First Name"
                                    :disabled="loading"
                                    v-model="form.firstname"
                                    placeholder="first name.."
                                    autocomplete="disabled"
                                    />
                                <b-input-with-validation
                                    icon="account-arrow-right"
                                    rules="alphanumspace|min:3"
                                    type="text"
                                    name="lastname"
                                    label="Last Name"
                                    :disabled="loading"
                                    v-model="form.lastname"
                                    placeholder="last name (optional)"
                                    autocomplete="disabled"
                                    />
                                <b-input-with-validation
                                    icon="cellphone-android"
                                    rules="required|phoneindo|min:12|max:15"
                                    type="phone"
                                    name="phone"
                                    label="Mobile Number"
                                    :disabled="loading"
                                    v-model="form.phone"
                                    v-cleave="masks.indophone"
                                    placeholder="08XX XXXX XXXX"
                                    autocomplete="disabled"
                                    />
                                <b-date-with-validation
                                    icon="calendar-today"
                                    rules="required"
                                    name="dob"
                                    label="Day of Birth"
                                    :disabled="loading"
                                    :max-date="dobMax"
                                    :date-creator="() => dobMax"
                                    v-model="form.dob"
                                    v-cleave="masks.dob"
                                    placeholder="Click to select date..."
                                    autocomplete="disabled"
                                    />
                                <b-radios-with-validation
                                    rules="required|oneOf:male,female"
                                    name="gender"
                                    vid="gender">
                                    <b-radio
                                        v-model="form.gender"
                                        :disabled="loading"
                                        name="gender"
                                        id="gender-male"
                                        native-value="male">
                                        Male
                                    </b-radio>
                                    <b-radio
                                        v-model="form.gender"
                                        :disabled="loading"
                                        name="gender"
                                        id="gender-female"
                                        native-value="female">
                                        Female
                                    </b-radio>
                                </b-radios-with-validation>
                                <hr style="margin:-1rem 0 1.5rem"/>
                                <b-input-with-validation
                                    icon="at"
                                    rules="required|email|emailunique"
                                    type="email"
                                    name="email"
                                    label="Email"
                                    :disabled="loading"
                                    v-model="form.email"
                                    placeholder="your email address.."
                                    autocomplete="disabled"
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
                                    autocomplete="new-pasword"
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
import Cleave from 'cleave.js'
require('cleave.js/dist/addons/cleave-phone.id')

/**
 * We add a new instance of Cleave when the element
 * is bound and destroy it when it's unbound.
 */
const cleave = {
    name: 'cleave',
    bind(el, binding) {
        const input = el.querySelector('input')
        input._vCleave = new Cleave(input, binding.value)
    },
    unbind(el) {
        const input = el.querySelector('input')
        input._vCleave.destroy()
    }
}

export default {
    name: 'Register',
    directives: { cleave },

    data() {
        const today = new Date()
        const dobTxt = (today.getFullYear()-1)+'-'+today.getMonth()+'-'+today.getDate()
        return {
            loading: false,
            isSuccess: false,
            dobMax: new Date(dobTxt),
            form: {
                phone: '',
                firstname: '',
                lastname: '',
                dob: [],
                gender: 'male',
                email: ''
            },
            masks: {
                indophone: {
                    phone: true,
                    phoneRegionCode: 'ID'
                },
                dob: {
                    date: true,
                    dateMax: dobTxt
                }
            }
        }
    },

    methods: {
        async submitForm() {
            this.loading = true
            const isValid = await this.$refs.observer.validate()

            if (isValid) {
                let formData = { ...this.form }
                formData.phone = formData.phone.replace(/\s+/g, '')
                formData.dob = this.dobFormat(formData.dob)
                await this.$http.put('register', formData).then(response => {
                    if (response.data && response.data.status) {
                        this.isSuccess = true
                        this.$refs.observer.reset()
                    } else if (response.status === 422) {
                        this.$refs.observer.setErrors(response.data)
                    }
                }).catch(err => {
                    this.$buefy.toast.open({
                        message: err.message || 'Registration failed, please try again',
                        type: 'is-danger',
                        duration: 4000
                    })
                }).finally(() => {
                    this.loading = false
                })
            }
        },
        goLoginPage() {
            this.$router.push({ name: 'login' })
        },
        dobFormat(value) {
            try {
                const date = (value instanceof Date) ? value : new Date(value)
                if (date) {
                    return date.getFullYear()+'-'+
                        ('0' + (date.getMonth()+1)).slice(-2)+'-'+
                        ('0' + date.getDate()).slice(-2)
                }
            } catch (error) {}
            return ''
        }
    }
}
</script>
