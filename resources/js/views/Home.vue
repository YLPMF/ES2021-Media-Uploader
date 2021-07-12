<template>
    <b-container>
        <b-card
            class="mt-5"
        >
            <b-form>

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

                <b-button href="#" variant="primary">Go somewhere</b-button>
            </b-form>
        </b-card>
    </b-container>
</template>

<script>
export default {
    name: "Home",
    data() {
        return {
            form: {
                country_code: null,
                archive: null,
            },
        }
    }
}
</script>

<style scoped>

</style>
