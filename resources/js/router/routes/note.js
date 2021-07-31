import NoteIndex from "@/app/note/components/NoteIndex";
import { authGuard } from "../guards";

export default [
    {
        path: "/note",
        component: NoteIndex,
        name: "note",
        meta: {
            requiresAuth: true,
            pageTitle: "Notes"
        },
        beforeEnter: authGuard
    }
];
