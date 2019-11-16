<template>
    <section class="hero is-danger is-bold is-fullheight-with-navbar has-text-centered">
        <div class="hero-body">
            <div class="container is-center">
                <div class="title"><h1 style="font-size:9rem;font-weight:bold">404</h1>Page Not Found</div>
                <p class="subtitle">
                Please check your type or <router-link to="/">Back to Home</router-link>
                </p>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'NotFound'
}
</script>
