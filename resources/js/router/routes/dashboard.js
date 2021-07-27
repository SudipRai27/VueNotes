import Dashboard from "@/app/user/components/Dashboard";
import { authGuard } from "../guards";

export default [
    {
        path: "/dashboard",
        component: Dashboard,
        name: "dashboard",
        meta: {
            requiresAuth: true,
            pageTitle: "Dashboard"
        },
        beforeEnter: authGuard
    }
];
