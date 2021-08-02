require("./bootstrap");
// require("./vuex/subscriber");

import Notifications from "vue-notification";
import router from "./router";
import store from "./vuex";

window.Vue = require("vue").default;

Vue.use(Notifications);
Vue.component("App", require("./components/App.vue").default);

Vue.prototype.$displayError = function (errMessage) {
    this.$notify({
        group: "notification",
        type: "error",
        title: "Error",
        text: errMessage,
        duration: 6000,
    });
};

Vue.prototype.$displaySuccess = function (successMessage) {
    this.$notify({
        group: "notification",
        type: "success",
        title: "Success",
        text: successMessage,
    });
};

axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (err) {
        switch (err.response.status) {
            case 401:
                const authenticated = store.getters["auth/authenticated"];
                if (authenticated) {
                    store.dispatch("auth/clearAuth").then(() => {
                        router.push({ name: "login" });
                    });
                }
                Vue.prototype.$displayError(err.response.data.message);
                return Promise.reject(err);
            case 422:
                Vue.prototype.$displayError("Validation error");
                return Promise.reject(err);
            case 403:
                Vue.prototype.$displayError(err.response.data.message);
                return Promise.reject(err);
            case 504:
                Vue.prototype.$displayError("Gateway Timeout");
                return Promise.reject(err);
            case 404:
                Vue.prototype.$displayError(err.response.data.message);
                return Promise.reject(err);
            case 400:
                Vue.prototype.$displayError(err.response.data.message);
                return Promise.reject(err);
            case 500:
                Vue.prototype.$displayError(err.response.data.message);
                return Promise.reject(err);
            default:
                return Promise.reject(err);
        }
    }
);

store
    .dispatch("auth/attempt", localStorage.getItem("authToken"))
    .finally(() => {
        const app = new Vue({
            el: "#app",
            router,
            store,
        });
    });
