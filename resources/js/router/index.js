import Vue from "vue";
import VueRouter from "vue-router";

import auth from "./routes/auth";
import home from "./routes/home";
import dashboard from "./routes/dashboard";
import errors from "./routes/errors";

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [...auth, ...home, ...dashboard, ...errors],
    mode: "history"
});

// alternative way for navigation guards. Currently it is done on individual specific guards
// router.beforeEach((to, from, next) => {
//     const authenticated = store.getters["auth/authenticated"];
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//         if (!authenticated) {
//             next({ name: "login" });
//         } else {
//             next();
//         }
//     } else if (to.matched.some(record => record.meta.guest)) {
//         if (!authenticated) {
//             next();
//         } else {
//             next({ name: "dashboard" });
//         }
//     } else {
//         next();
//     }
// });
export default router;
