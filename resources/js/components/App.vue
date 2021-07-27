<template>
  <div>
    <notifications group="notification" />
    <Navigation></Navigation>
    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";
export default {
  components: { Navigation },
  //   watch: {
  //     $route(to, from) {
  //       document.title = to.meta.pageTitle || "SPA";
  //     },
  //   },
};
</script>

