<template>
    <b-container>
        <b-row>
            <b-table striped hover :items="uploads" :fields="fields">

                <template #cell(actions)="data">
                    <b-button v-b-modal.modal-1 @click="getFiles(data.item.id)">View</b-button>
                </template>

            </b-table>
        </b-row>

        <b-modal id="modal-1" title="BootstrapVue">
            <b-table striped hover :items="files" :fields="file_fields">
                <template #cell(status)="data">
                    {{ data.item.status ? 'Verified' : 'Unverified' }}
                </template>

                <template #cell(actions)="data">
                    <b-button @click="toggleFileStatus(data.item.upload_id, data.item.id, !data.item.status)">{{ !data.item.status ? 'Verify' : 'Deny' }}</b-button>
                </template>
            </b-table>
        </b-modal>

    </b-container>
</template>

<script>
export default {
    name: "Admin",
    data() {
        return {
            file_fields: [
                {
                    key: 'file_name',
                    label: 'File name',
                },
                {
                    key: 'status',
                    label: 'Status',
                },
                {
                    key: 'actions',
                    label: 'Actions',
                },
            ],
            fields: [
                {
                    key: 'id',
                    label: '#',
                },
                {
                    key: 'archive_name',
                    label: 'Archive name',
                },
                {
                    key: 'country_code',
                    label: 'Country code',
                },
                {
                    key: 'created_at',
                    label: 'Uploaded at',
                },
                {
                    key: 'ip_address',
                    label: 'IP',
                },
                {
                    key: 'actions',
                    label: 'Actions',
                },
            ],
            uploads: [],
            files: [],
        }
    },
    mounted() {
        let token = localStorage.getItem('token');

        if(token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            axios.get('/api/me').catch(response => {
                this.$router.push({ name: 'Login' })
            })
        }

        this.getUploads();
    },
    methods: {
        getUploads() {
            axios.get('/api/admin/uploads').then(response => {
                this.uploads = response.data;
            })
        },
        getFiles(upload_id) {
            axios.get('/api/admin/files/'+upload_id).then(response => {
                this.files = response.data;
            })
        },
        toggleFileStatus(upload_id, file_id, status) {
            axios.put('/api/admin/files/'+file_id, {
                'status': status
            }).then(response => {
                this.getFiles(upload_id);
            })
        },
    }
}
</script>

<style scoped>

</style>
