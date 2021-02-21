<template>
  <div class="w-100" id="background">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow border-0">
            <div class="card-body">
              <b-alert
                show
                dismissible
                variant="danger"
                v-for="(error, index) in errors"
                :key="index"
              >
                {{ error.message }} <b>&rArr;</b>
              </b-alert>
              <h3 class="display-4">Login</h3>
              <form>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input
                    type="email"
                    id="username"
                    v-model="form.username"
                    class="form-control"
                  />
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input
                    type="password"
                    v-model="form.password"
                    id="password"
                    class="form-control"
                  />
                </div>
                <div class="form-group row justify-content-center">
                  <div class="col-md-5">
                    <button
                      class="btn btn-primary btn-block"
                      @click="handleLogin"
                      type="button"
                    >
                      Login
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      //eslint-disable-next-line
      reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
      form: {
        username: "",
        password: "",
      },
      errors: [],
      isLoading: false,
    };
  },
  methods: {
    validateInput: function () {
      if (this.form.password === "") {
        this.errors.push({ message: "password cannot be empty" });
        return false;
      } else if (this.form.username === "") {
        this.errors.push({ message: "email cannot be empty" });
        return false;
      } else if (this.form.username !== "") {
        if (!this.reg.test(this.form.username)) {
          this.errors.push({ message: "invalid email" });
          return false;
        }
      }

                return true;
            },
            handleLogin: function(e) {
                e.preventDefault();
                if (this.validateInput()) {
                    this.$store
                        .dispatch(
                            "login",
                            {
                                username: this.form.username,
                                password: this.form.password,
                                client_id: 3,
                                client_secret:
                                    "PPNizGBuXqisZ51o11FyNLhHuqypGzyapT43sfHq",
                                grant_type: "password"
                            },
                            { root: true }
                        )
                        .then(() => {
                            this.$router.push("/home");
                        })
                        .catch(error => {
                            console.log(error);
                            this.errors.push({message: error});
                        });
                    this.loading = false;
                }
            }
        }
    }
</script>

<style scoped>
#background {
  background-image: linear-gradient(45deg, rgb(23, 24, 68), rgb(143, 219, 193));
  height: 100vh;
}
</style>
