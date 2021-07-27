require("./bootstrap");
// require("./vuex/subscriber");

import Notifications from "vue-notification";
import router from "./router";
import store from "./vuex";

window.Vue = require("vue").default;

Vue.use(Notifications);
Vue.component("App", require("./components/App.vue").default);

Vue.prototype.$displayError = function(errMessage) {
    this.$notify({
        group: "notification",
        type: "error",
        title: "Error",
        text: errMessage,
        duration: 6000
    });
};

Vue.prototype.$displaySuccess = function(successMessage) {
    this.$notify({
        group: "notification",
        type: "success",
        title: "Success",
        text: successMessage
    });
};

store
    .dispatch("auth/attempt", localStorage.getItem("authToken"))
    .finally(() => {
        const app = new Vue({
            el: "#app",
            router,
            store
        });

        axios.interceptors.response.use(
            function(response) {
                return response;
            },
            function(err) {
                switch (err.response.status) {
                    case 401:
                        const authenticated =
                            store.getters["auth/authenticated"];
                        if (authenticated) {
                            store.dispatch("auth/clearAuth").then(() => {
                                router.push({ name: "login" });
                            });
                        }
                        app.$displayError(err.response.data.message);
                        return Promise.reject(err);
                    case 422:
                        app.$displayError("Validation error");
                        return Promise.reject(err);
                    case 403:
                        app.$displayError(err.response.data.message);
                        return Promise.reject(err);
                    case 504:
                        app.$displayError("Gateway Timeout");
                        return Promise.reject(err);
                    case 404:
                        app.$displayError(err.response.data.message);
                        return Promise.reject(err);
                    case 400:
                        app.$displayError(err.response.data.message);
                        return Promise.reject(err);
                    case 500:
                        app.$displayError(err.response.data.message);
                        return Promise.reject(err);
                    default:
                        return Promise.reject(err);
                }
            }
        );
    });
