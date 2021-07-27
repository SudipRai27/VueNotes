import Login from "@/app/auth/components/Login";
import Register from "@/app/auth/components/Register";
import { guestGuard } from "../guards";

export default [
    {
        path: "/login",
        component: Login,
        name: "login",
        meta: {
            guest: true,
            pageTitle: "Login"
        },
        beforeEnter: guestGuard
    },
    {
        path: "/register",
        component: Register,
        name: "register",
        meta: {
            guest: true,
            pageTitle: "Register"
        },
        beforeEnter: guestGuard
    }
];
