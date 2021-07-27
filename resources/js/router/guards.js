import store from "@/vuex";

export const authGuard = (to, from, next) => {
    document.title = to.meta.pageTitle || "SPA";
    const authenticated = store.getters["auth/authenticated"];
    if (authenticated) {
        next();
    } else {
        next({ name: "login" });
    }
};

export const guestGuard = (to, from, next) => {
    document.title = to.meta.pageTitle || "SPA";
    const authenticated = store.getters["auth/authenticated"];
    if (!authenticated) {
        next();
    } else {
        next({ name: "dashboard" });
    }
};
