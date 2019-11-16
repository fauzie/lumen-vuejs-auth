import Vue from 'vue'
import {
    ValidationProvider,
    ValidationObserver,
    configure,
    extend
} from 'vee-validate'
import {
    required,
    email,
    alpha_num,
    min
} from 'vee-validate/dist/rules'
import BInputWithValidation from './inputs/BInputWithValidation'
import BRadiosWithValidation from './inputs/BRadiosWithValidation'
import BSelectWithValidation from './inputs/BSelectWithValidation'

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
extend('alphanum', {
    validate: (value) => /(?=.*\d)(?=.*[a-zA-Z])/.test(value),
    message: 'at least one alphabet and numeric'
})
extend('min', min)

Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)
Vue.component('BInputWithValidation', BInputWithValidation)
Vue.component('BRadiosWithValidation', BRadiosWithValidation)
Vue.component('BSelectWithValidation', BSelectWithValidation)
