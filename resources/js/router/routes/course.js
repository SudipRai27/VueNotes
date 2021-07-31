import CourseIndex from "@/app/course/components/CourseIndex";
import { authGuard } from "../guards";

export default [
    {
        path: "/course",
        component: CourseIndex,
        name: "course",
        meta: {
            requiresAuth: true,
            pageTitle: "Courses"
        },
        beforeEnter: authGuard
    }
];
