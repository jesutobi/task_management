import { createRouter, createWebHistory } from "vue-router";
import Tasks from "../components/task_manager.vue";
// import Projects from "../components/Projects.vue";

const routes = [
    { path: "/tasks", component: Tasks },
    // { path: "/projects", component: Projects },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
