<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="#">Media Uploader</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item href="#">Link</b-nav-item>
                    <b-nav-item href="#" disabled>Disabled</b-nav-item>
                </b-navbar-nav>

            </b-collapse>
        </b-navbar>

        <router-view></router-view>
    </div>

</template>
<script>
const default_layout = "default";

export default {
    computed: {},
    data() {
        return {
            message:'Hello World'
        }
    }
};
</script>
<style scoped>
a.navbar-brand {
    margin-left: 20px;
}
</style>
