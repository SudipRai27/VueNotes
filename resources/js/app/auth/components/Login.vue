<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Login</div>
          <div class="card-body">
            <form method="POST" @submit.prevent="submit">
              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right"
                  >Email Address</label
                >
                <div class="col-md-6">
                  <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    autocomplete="email"
                    v-model="form.email"
                    @input="removeErrors('email')"
                  />
                  <span
                    class="invalid-feedback"
                    style="display: block"
                    role="alert"
                    v-if="errors.email"
                  >
                    {{ errors.email[0] }}
                  </span>
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="password"
                  class="col-md-4 col-form-label text-md-right"
                  >Passsword</label
                >
                <div class="col-md-6">
                  <input
                    id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    autocomplete="new-password"
                    v-model="form.password"
                    @input="removeErrors('password')"
                  />
                  <span
                    class="invalid-feedback"
                    style="display: block"
                    role="alert"
                    v-if="errors.password"
                  >
                    {{ errors.password[0] }}
                  </span>
                </div>
              </div>
              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { removeValidationErrors } from "@/helpers";
export default {
  name: "Login",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      errors: [],
    };
  },
  methods: {
    ...mapActions({
      login: "auth/login",
    }),
    submit() {
      this.login(this.form)
        .then((res) => {
          this.$router.replace({ name: "dashboard" });
        })
        .catch((err) => {
          if (err.response && err.response.status === 422) {
            this.errors = err.response.data.errors;
          }
        });
    },
    removeErrors(field) {
      this.errors = removeValidationErrors(this.errors, field);
    },
  },
};
</script>
