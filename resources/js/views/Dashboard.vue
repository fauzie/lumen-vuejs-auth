<template>
    <section class="hero is-fullheight-with-navbar">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">Dashboard</h1>
                <pre>{{ user | pretty }}</pre>
            </div>
        </div>
    </section>
</template>

<script>
import { mapState } from 'vuex'

export default {
    name: 'Dashboard',
    computed: mapState(['user']),
    filters: {
        pretty: function(value) {
            return JSON.stringify(value, null, 2);
        }
    }
}
</script>
