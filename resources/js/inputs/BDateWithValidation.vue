<template>
    <ValidationProvider
        :vid="vid"
        :name="$attrs.name || $attrs.label"
        :rules="rules"
        v-slot="{ errors, valid }"
        >
        <b-field
            v-bind="$attrs"
            :type="{ 'is-danger': errors[0], 'is-success': valid }"
            :message="errors"
        >
            <b-datepicker v-model="innerValue" :max-date="maxDate" v-bind="$attrs"></b-datepicker>
        </b-field>
        <p>&nbsp;</p>
    </ValidationProvider>
</template>

<script>
export default {
    props: {
        vid: {
            type: String
        },
        maxDate: {
            type: Date,
            default: new Date(8640000000000000)
        },
        rules: {
            type: [Object, String],
            default: ""
        },
        value: {
            type: null
        }
    },
    data: () => ({
        innerValue: ''
    }),
    watch: {
        innerValue(newVal) {
            this.$emit('input', newVal)
        },
        value(newVal) {
            this.innerValue = (newVal && !(newVal instanceof Date)) ? new Date(Date.parse(newVal)) : newVal
        }
    },
    created() {
        if (this.value) {
            this.innerValue = this.value
        }
    }
}
</script>
