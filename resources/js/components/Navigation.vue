<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <router-link :to="{ name: 'home' }" class="navbar-brand">
        SPA
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle Navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto"></ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->

          <template v-if="!authenticated">
            <li class="nav-item">
              <router-link :to="{ name: 'login' }" class="nav-link">
                Login
              </router-link>
            </li>

            <li class="nav-item">
              <router-link :to="{ name: 'register' }" class="nav-link">
                Register</router-link
              >
            </li>
          </template>
          <template v-else>
            <li class="nav-item dropdown">
              <router-link
                id="navbarDropdown"
                class="nav-link"
                href="#"
                role="button"
                :to="{ name: 'dashboard' }"
              >
                Dashboard
              </router-link>
            </li>
            <li class="nav-item dropdown">
              <a
                id="navbarDropdown"
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                {{ user.name }}
              </a>

              <div
                class="dropdown-menu dropdown-menu-right"
                aria-labelledby="navbarDropdown"
              >
                <a class="dropdown-item" @click.prevent="signOut"> Logout</a>
              </div>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  name: "Navigation",
  computed: {
    ...mapGetters({
      authenticated: "auth/authenticated",
      user: "auth/user",
    }),
  },
  methods: {
    ...mapActions({
      logout: "auth/logout",
    }),
    async signOut() {
      try {
        await this.logout();
        this.$router.replace({ name: "login" });
      } catch (e) {}
    },
  },
};
</script>
