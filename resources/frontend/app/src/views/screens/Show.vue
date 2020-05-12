<template>
    <div class="container-fluid" v-if="currentScreen">
        <div class="border-bottom d-flex justify-content-between">
            <h1>{{ currentScreen.title }}</h1>
            <b-button v-b-modal.new-attachment variant="primary">New Attachment</b-button>
            <b-modal id="new-attachment" title="New Attachment" @ok="submitAttachment">
                <form>
                    <div class="form-group">
                        <label for="attachment-title">Title</label>
                        <input type="text" v-model="form.title" id="attachment-title" class="form-control">
                    </div>
                    <div class="form-group"><label for="attachment-type">Type</label>
                        <select v-model="form.type" id="attachment-type" class="form-control">
                            <option value="">Choose</option>
                            <option value="quote">Quote</option>
                            <option value="image">Image</option>
                            <option value="youtube">Youtube</option>
                            <option value="tweet">Tweet</option>
                        </select>
                    </div>
                    <div class="form-group" v-if="form.type === 'quote'">
                        <label for="attachment-text">Quote Text</label>
                        <textarea v-model="form.text" id="attachment-text" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="form-group" v-if="form.type === 'quote' || form.type === 'image'">
                        <label for="attachment-file">Image</label>
                        <input type="file" id="attachment-file" @change="appendImage" class="form-control">
                    </div>
                    <div class="form-group" v-if="form.type === 'youtube' || form.type === 'tweet'">
                        <label for="attachment-link">Link</label>
                        <input type="text" id="attachment-link" v-model="form.link" class="form-control">
                    </div>
                </form>
            </b-modal>
        </div>
        <div class="py-2" v-if="allAttachments && allAttachments.length > 0">
            <h3>Attachments</h3>
            <b-list-group>
                <b-list-group-item class="flex-column align-items-start shadow border-0" v-for="(attachment, index) in allAttachments" :key="index">
                    <b-media>
                        <template v-slot:aside>
                            <b-img v-if="attachment.type !== 'youtube' && attachment.type !== 'tweet'" :src="attachment.image" width="64" alt="placeholder"></b-img>
                            <b-img v-else-if="attachment.type === 'tweet'" :src="attachment.tweetInfo.images[0]" width="64" alt="placeholder"></b-img>
                            <b-img v-else blank blank-color="#ccc" width="64" alt="placeholder"></b-img>
                        </template>

                        <h5 class="mt-0">{{ attachment.title }}</h5>
                        <p v-if="attachment.type === 'quote'">
                            {{ attachment.text }}
                        </p>
                        <iframe v-if="attachment.type === 'youtube'" width="560" height="315" :src="'https://www.youtube.com/embed/'+attachment.youtube" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <div class="col-md-6" v-if="attachment.type === 'tweet'">
                            <img :src="image" class="img-fluid col-md-6 p-0" v-for="(image, index) in attachment.tweetInfo.images" :key="index">
                        </div>
                        <div class="d-block mt-2">
                            <b-button v-b-modal="'delete-confirmation-'+attachment.id" variant="danger">Delete</b-button>
                            <b-modal title="Delete Confirmation" :id="'delete-confirmation-'+attachment.id" @ok="handleDelete(attachment.id)">
                                <h4 class="text-center">Are you sure?</h4>
                            </b-modal>
                        </div>
                    </b-media>
                </b-list-group-item>
            </b-list-group>
        </div>
        <h4 class="text-center py-2" v-else>No Attachments</h4>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    export default {
        name: "Show",
        data(){
            return {
                form: {
                    title:'',
                    type:'',
                    text:'',
                    image: '',
                    link: ''
                },
                errors:[],
                isLoading: false
            }
        },
        mounted() {
            this.fetchScreen(this.$route.params.id);
            this.fetchAttachments(this.$route.params.id);
        },
        computed: {
            ...mapGetters(['currentScreen','allAttachments'])
        },
        methods: {
            ...mapActions(['fetchScreen','fetchAttachments','createAttachment']),
            submitAttachment: function(e)
            {
                e.preventDefault();
                var fd = new FormData();
                Object.keys(this.form).forEach(key => {
                    fd.append(key, this.form[key]);
                });

                const payload = {screen: this.currentScreen.id, form: fd};

                this.$store.dispatch('createAttachment', payload)
                .then(() => {
                    this.$swal('Great!', 'Attachment created successfully', 'success');
                    this.$bvModal.hide('new-attachment');
                    this.form = {
                        title:'',
                        type:'',
                        text:'',
                        image: '',
                        link: ''
                    }
                }).catch((error) => {
                    this.$swal('Oops!', error, 'error');
                    this.$bvModal.hide('new-attachment');
                })
            },
            appendImage: function(e)
            {
                this.form.image = e.target.files[0]
            },
            handleDelete: function(attachment)
            {
                const payload =  {attache: attachment, screen: this.currentScreen.id};
                this.$store.dispatch('deleteAttachment', payload)
                    .then(() => {
                        this.isLoading = false;
                        this.$swal('Done!', 'Attachment Deleted successfully', 'success');
                        this.$bvModal.hide('delete-confirmation-'+attachment);
                    }).catch((error) => {
                    this.isLoading = false;
                    this.$swal('Oops', error, 'error');
                });
            }
        }
    }
</script>

<style scoped>

</style>
