<template>
    <ValidationProvider :vid="vid" :name="$attrs.name || $attrs.label" :rules="rules" v-slot="{ errors }">
        <div class="block has-text-left">
            <slot/>
            <p class="has-text-danger">{{ errors[0] }}</p>
        </div>
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
        }
    }
}
</script>
