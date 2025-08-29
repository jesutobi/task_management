import "./bootstrap";

import "../css/app.css";
import { createApp } from "vue";

import router from "./router";
import TaskManager from "./components/task_manager.vue";

createApp(TaskManager).use(router).mount("#app");
