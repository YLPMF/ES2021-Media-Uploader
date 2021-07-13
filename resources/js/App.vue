<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="#">Media Uploader</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
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
