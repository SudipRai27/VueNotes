import NotFound from "@/app/errors/components/NotFound";

export default [
    {
        path: "*",
        component: NotFound,
        name: "404",
        meta: {
            pageTitle: "404"
        }
    }
];
