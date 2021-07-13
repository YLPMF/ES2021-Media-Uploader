<template>
    <b-container>
        <b-card
            class="mt-5"
        >

            <b-form @submit="onSubmit">

                <b-form-group label="Username" label-cols-sm="2" label-size="sm">
                    <b-form-input
                        id="input-1"
                        v-model="form.username"
                        type="text"
                        placeholder="Username"
                        required
                    ></b-form-input>
                </b-form-group>

                <b-form-group label="Password" label-cols-sm="2" label-size="sm">
                    <b-form-input
                        id="input-1"
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        required
                    ></b-form-input>
                </b-form-group>

                <b-button type="submit" variant="primary" class="mt-2">Login</b-button>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
export default {
    name: "Admin",
    data() {
        return {
            form: {
                username: null,
                password: null,
            }
        }
    },
    mounted() {
        let token = localStorage.getItem('token');

        if(token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            axios.get('/api/me').then(response => {
              this.$router.push({ name: 'Dashboard' })
            })
        }
    },
    methods: {
        onSubmit(event) {
            event.preventDefault();

            axios.post('/api/login', this.form).then(response => {
                localStorage.setItem('token', response.data.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
                this.$router.push({ name: 'Dashboard' })
            }).catch(error => {
                this.errors = error.response.data.errors;
            })
        },
    },
}
</script>

<style scoped>

</style>
