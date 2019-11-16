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
            <b-input v-model="innerValue" v-bind="$attrs"></b-input>
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
        rules: {
            type: [Object, String],
            default: ""
        },
        value: {
            type: null
        }
    },
    data: () => ({
        innerValue: ""
    }),
    watch: {
        innerValue(newVal) {
            this.$emit("input", newVal)
        },
        value(newVal) {
            this.innerValue = newVal
        }
    },
    created() {
        if (this.value) {
            this.innerValue = this.value
        }
    }
}
</script>
