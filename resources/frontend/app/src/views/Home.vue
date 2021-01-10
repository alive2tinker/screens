<template>
  <div class="container-fluid">
    <div class="loading" v-if="isLoading">Loading</div>
    <div class="border-bottom d-flex justify-content-between">
      <h1>Screens</h1>
      <button v-b-modal.new-screen class="btn btn-primary">New Screen</button>
      <b-modal id="new-screen" title="New Screen" @ok="submitForm">
        <form>
          <div class="form-group">
            <label for="screen-title">Title</label>
            <input
              v-model="form.title"
              id="screen-title"
              type="text"
              class="form-control"
            />
          </div>
        </form>
      </b-modal>
    </div>
    <div v-if="allScreens.length > 0">
      <b-list-group class="mt-4">
        <b-list-group-item
          class="flex-column align-items-start shadow border-0"
          v-for="(screen, index) in allScreens"
          :key="index"
        >
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">
              <router-link
                :to="{ name: 'screens.show', params: { id: screen.id } }"
                >{{ screen.title }}</router-link
              >
            </h5>
            <small>{{ screen.createdAt }}</small>
          </div>

          <p class="mb-1">Attachments: {{ screen.attachments.length }}</p>

          <small>
            <router-link
              :to="{ name: 'screens.view', params: { id: screen.id } }"
              class="btn btn-success"
              >View</router-link
            >
            <button
              v-b-modal="'edit-modal-' + screen.id"
              class="btn btn-secondary"
            >
              Edit
            </button>
            <button
              v-b-modal="'delete-confirmation-' + screen.id"
              class="btn btn-danger"
            >
              Delete
            </button>
          </small>
          <b-modal
            :id="'edit-modal-' + screen.id"
            :title="'Edit ' + screen.title"
          >
            <p class="my-4">Hello from modal!</p>
          </b-modal>
          <b-modal
            :id="'delete-confirmation-' + screen.id"
            title="Delete Confirmation"
            @ok="handleDelete(screen.id)"
          >
            <p class="my-4 text-center">Are you sure?</p>
          </b-modal>
        </b-list-group-item>
      </b-list-group>
    </div>
    <h4 class="text-center my-5" v-else>
      No Screens have been created. create one now
    </h4>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  name: "Home",
  data() {
    return {
      form: {
        title: "",
      },
      errors: [],
      isLoading: false,
    };
  },
  mounted() {
    this.isLoading = true;
    this.fetchScreens().then(() => {
      this.isLoading = false;
    });
  },
  computed: {
    ...mapGetters(["currentScreen", "allScreens"]),
  },
  methods: {
    ...mapActions([
      "fetchScreens",
      "fetchMoreScreens",
      "createScreen",
      "updateScreen",
      "deleteScreen",
    ]),
    submitForm: function (e) {
      e.preventDefault();
      this.isLoading = true;
      var fd = new FormData();
      Object.keys(this.form).forEach((key) => {
        fd.append(key, this.form[key]);
      });

      this.$store
        .dispatch("createScreen", fd)
        .then(() => {
          this.isLoading = false;
          this.$swal("Great!", "Project Created successfully", "success");
          this.$bvModal.hide("new-screen");
        })
        .catch((error) => {
          this.isLoading = false;
          this.$swal("Oops", error.data.message, "error");
        });
    },
    handleDelete: function (id) {
      this.isLoading = true;
      this.$store
        .dispatch("deleteScreen", id)
        .then(() => {
          this.isLoading = false;
          this.$swal("Done!", "Screen Deleted successfully", "success");
          this.$bvModal.hide("delete-confirmation-" + id);
        })
        .catch((error) => {
          this.isLoading = false;
          this.$swal("Oops", error.data.message, "error");
        });
    },
  },
};
</script>

<style scoped>
/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: "";
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(rgba(23, 24, 68, 0.5), rgba(23, 24, 68, 0.8));

  background: radial-gradient(rgba(23, 24, 68, 0.5), rgba(23, 24, 68, 0.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: "";
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0,
    rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0,
    rgba(255, 255, 255, 0.75) 0 1.5em 0 0,
    rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0,
    rgba(255, 255, 255, 0.75) -1.5em 0 0 0,
    rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0,
    rgba(255, 255, 255, 0.75) 0 -1.5em 0 0,
    rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
  box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0,
    rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0,
    rgba(255, 255, 255, 0.75) 0 1.5em 0 0,
    rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0,
    rgba(255, 255, 255, 0.75) -1.5em 0 0 0,
    rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0,
    rgba(255, 255, 255, 0.75) 0 -1.5em 0 0,
    rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
