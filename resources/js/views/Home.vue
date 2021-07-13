<template>
    <b-container>
        <b-card
            class="mt-5"
        >
            <b-alert variant="success" :show="uploaded" dismissible>
                The archive has been successfully uploaded
            </b-alert>

            <b-alert variant="danger" v-for="(error, index) in errors" :key="index" show dismissible>
                <div v-for="(e, i) in error">{{ e }}</div>
            </b-alert>

            <b-form @submit="onSubmit">

                <b-form-group label="Country code" label-cols-sm="2" label-size="sm">
                    <b-form-input
                        id="input-1"
                        v-model="form.country_code"
                        type="text"
                        placeholder="Country code"
                        maxlength="2"
                        required
                    ></b-form-input>
                </b-form-group>


                <b-form-group label="Archive with songs" label-cols-sm="2" label-size="sm">
                    <b-form-file v-model="form.archive" ref="file-input" accept=".zip" class="mb-2"></b-form-file>
                </b-form-group>

                <b-button type="submit" variant="primary">Go somewhere</b-button>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
import axios from 'axios'

export default {
    name: "Home",
    data() {
        return {
            form: {
                country_code: null,
                archive: null,
            },
            errors: [],
            uploaded: false,
        }
    },
    methods: {
      onSubmit(event) {
          event.preventDefault();

          let formData = new FormData();

          formData.append('country_code', this.form.country_code);
          formData.append('archive', this.form.archive);

          axios.post('/api/upload', formData).then(response => {
              this.uploaded = true;
          }).catch(error => {
            this.errors = error.response.data.errors;
          })
      },
    },
}
</script>

<style scoped>

</style>
