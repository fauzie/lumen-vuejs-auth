import Vue from 'vue'
import http from './http'
import {
    ValidationProvider,
    ValidationObserver,
    configure,
    extend
} from 'vee-validate'
import {
    required,
    email,
    min,
    max,
    oneOf
} from 'vee-validate/dist/rules'
import BInputWithValidation from './inputs/BInputWithValidation'
import BDateWithValidation from './inputs/BDateWithValidation'
import BRadiosWithValidation from './inputs/BRadiosWithValidation'
import BSelectWithValidation from './inputs/BSelectWithValidation'

let cacheExistsEmails = {}
let cacheUniqueEmails = {}

configure({
    classes: {
        valid: 'is-success',
        invalid: 'is-danger'
    },
    bails: true,
    skipOptional: true,
    mode: 'aggressive',
    useConstraintAttrs: true
})

extend('required', {
    ...required,
    message: 'This field is required'
})
extend('email', {
    ...email,
    message: 'Email address must contain @ and valid domain name'
})
extend('emailexists', {
    lazy: true,
    message: 'Email not exists on database',
    validate: (value) => {
        if (!value || value.length === 0) return true
        if (cacheExistsEmails.hasOwnProperty(value)) return cacheExistsEmails[value]
        return new Promise((resolve, reject) => {
            http.post('exists', {email:value}).then(response => {
                cacheExistsEmails[value] = response.data.status
                resolve(response.data && response.data.status)
            }).catch(reject)
        })
    }
})
extend('emailunique', {
    lazy: true,
    message: 'Email already registered, please use another',
    validate: (value) => {
        if (!value || value.length === 0) return true
        if (cacheUniqueEmails.hasOwnProperty(value)) return cacheUniqueEmails[value]
        return new Promise((resolve, reject) => {
            http.post('unique', {email:value}).then(response => {
                cacheUniqueEmails[value] = response.data.status
                resolve(response.data && response.data.status)
            }).catch(reject)
        })
    }
})
extend('alphanum', {
    validate: (value) => /(?=.*\d)(?=.*[a-zA-Z])/.test(value),
    message: 'at least one alphabet and numeric'
})
extend('alphanumspace', {
    validate: (value) => /^\w+( +\w+)*$/.test(value),
    message: 'only alphabet, number and space allowed'
})
extend('phoneindo', {
    validate: (value) => /^(^\+62\s?|^0)(\d{3,4}[\s|-]?){2}\d{3,4}$/.test(value),
    message: '{_field_} format invalid. example: 0812 4321 0000'
})
extend('min', min)
extend('max', max)
extend('oneOf', oneOf)

Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)
Vue.component('BDateWithValidation', BDateWithValidation)
Vue.component('BInputWithValidation', BInputWithValidation)
Vue.component('BRadiosWithValidation', BRadiosWithValidation)
Vue.component('BSelectWithValidation', BSelectWithValidation)
