<template>
<div class="container-fluid">
    <div class="border-bottom d-flex justify-content-between">
        <h1>Screens</h1>
        <button v-b-modal.new-screen class="btn btn-primary">New Screen</button>
        <b-modal id="new-screen" title="New Screen" @ok="submitForm">
            <form>
                <div class="form-group">
                    <label for="screen-title">Title</label>
                    <input v-model="form.title" id="screen-title" type="text" class="form-control"></div>
            </form>
        </b-modal>
    </div>
    <b-list-group class="mt-4">
        <b-list-group-item  class="flex-column align-items-start shadow border-0" v-for="(screen, index) in allScreens" :key="index">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><router-link :to="{name: 'screens.show', params:{id: screen.id}}">{{ screen.title }}</router-link></h5>
                <small>{{ screen.createdAt }}</small>
            </div>

            <p class="mb-1">
                Attachments: {{ screen.attachments.length }}
            </p>

            <small>
                <router-link :to="{name: 'screens.view', params:{id: screen.id}}" class="btn btn-success">View</router-link>
                <button v-b-modal="'edit-modal-'+screen.id" class="btn btn-secondary">Edit</button>
                <button v-b-modal="'delete-confirmation-'+screen.id" class="btn btn-danger">Delete</button>
            </small>
            <b-modal :id="'edit-modal-'+screen.id" :title="'Edit '+screen.title">
                <p class="my-4">Hello from modal!</p>
            </b-modal>
            <b-modal :id="'delete-confirmation-'+screen.id" title="Delete Confirmation" @ok="handleDelete(screen.id)">
                <p class="my-4 text-center">Are you sure?</p>
            </b-modal>
        </b-list-group-item>
    </b-list-group>
</div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    export default {
        name: "Home",
        data(){
            return {
                form: {
                    title: ''
                },
                errors: [],
                isLoading: false
            }
        },
        mounted() {
          this.fetchScreens();
        },
        computed:{
            ...mapGetters(['currentScreen','allScreens'])
        },
        methods:{
            ...mapActions(['fetchScreens','fetchMoreScreens', 'createScreen','updateScreen','deleteScreen']),
            submitForm: function(e) {
                e.preventDefault();
                var fd = new FormData();
                Object.keys(this.form).forEach(key => {
                    fd.append(key, this.form[key]);
                });

                this.$store.dispatch('createScreen', fd)
                    .then(() => {
                        this.loading = false;
                        this.$swal('Great!', "Project Created successfully", 'success');
                        this.$bvModal.hide('new-screen');
                    }).catch((error) => {
                    this.loading = false;
                    this.$swal('Oops', error.data.message, 'error')
                })
            },
            handleDelete: function(id){
                this.$store.dispatch('deleteScreen', id)
                .then(() => {
                    this.isLoading = false;
                    this.$swal('Done!', 'Screen Deleted successfully', 'success');
                    this.$bvModal.hide('delete-confirmation-'+id);
                }).catch((error) => {
                    this.isLoading = false;
                    this.$swal('Oops', error.data.message, 'error');
                });
            }
        }
    }
</script>

<style scoped>

</style>
