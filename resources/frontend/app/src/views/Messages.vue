<template>
    <div class="container-fluid">
        <div class="border-bottom">
            <h1 class="display-4">Messages</h1>
        </div>
        <b-input-group prepend="Message Text" class="mt-3">
            <b-form-input v-model="form.text"></b-form-input>
            <b-input-group-append>
                <b-button variant="outline-success" @click="submitForm">submit</b-button>
            </b-input-group-append>
        </b-input-group>
        <b-list-group class="my-3" v-if="allMessages.length > 0">
            <b-list-group-item v-for="message in allMessages" :key="message.id">
                <div class="d-flex justify-content-between">
                    <p>{{ message.text }}</p>
                    <b-button-group>
                        <b-button @click="showEditModal(message)">Edit</b-button>
                        <b-button variant="danger" v-b-modal="'delete-confirmation-'+message.id">Delete</b-button>
                    </b-button-group>
                    <b-modal :id="'edit-modal-'+message.id" title="editing message" @ok="handleUpdate(message)">
                        <div class="form-group">
                            <label for="old-message-text">Text</label>
                            <input id="old-message-text" v-model="editForm.text" type="text" class="form-control">
                        </div>
                    </b-modal>
                    <b-modal title="Delete Confirmation" :id="'delete-confirmation-'+message.id" @ok="handleDelete(message.id)">
                        <h4 class="text-center">Are you sure?</h4>
                    </b-modal>
                </div>
            </b-list-group-item>
        </b-list-group>
        <h4 v-else class="text-center py-3">No Messages</h4>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    export default {
        name: "Messages",
        data(){
            return{
                form: {
                    text: ''
                },
                editForm: {
                    text: ''
                }
            }
        },
        mounted() {
            this.fetchMessages();
        },
        computed: {
            ...mapGetters(['allMessages'])
        },
        methods: {
            ...mapActions(['fetchMessages','createMessage','updateMessage','deleteMessage']),
            submitForm: function(e)
            {
                e.preventDefault();
                var fd = new FormData();
                Object.keys(this.form).forEach(key => {
                    fd.append(key, this.form[key]);
                });

                this.$store.dispatch('createMessage', fd)
                .then(() => {
                    this.$swal('Great!', 'Message saved successfully', 'success');
                    this.form = {text: ''}
                }).catch((error) => {
                    this.$swal('error', error.data.message, 'error');
                });
            },
            showEditModal: function(message)
            {
              this.editForm.text = message.text;
              this.$bvModal.show('edit-modal-'+message.id)
            },
            handleUpdate: function(message)
            {
                var fd = new FormData();
                Object.keys(this.editForm).forEach(key => {
                    fd.append(key, this.editForm[key]);
                });

                fd.append('_method', 'PATCH');

                const payload = {id: message.id, form: fd};
                this.$store.dispatch('updateMessage', payload)
                    .then(() => {
                        this.$swal('Great!', 'Message updated successfully', 'success');
                        this.editForm = {text: ''}
                    }).catch((error) => {
                    this.$swal('error', error.data.message, 'error');
                });
            },
            handleDelete: function(message)
            {
                this.$store.dispatch('deleteMessage', message)
                .then(() => {
                    this.$swal('Great!', 'message deleted successfully', 'success');
                }).catch((error) => {
                    this.$swal('Oops!', error.data.message, 'error');
                })
            }
        }
    }
</script>

<style scoped>

</style>
