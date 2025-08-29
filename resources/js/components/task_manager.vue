<template>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4"
    >
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <h1
                    class="text-3xl font-bold text-gray-800 flex items-center gap-2"
                >
                    <span
                        class="inline-block w-3 h-3 bg-blue-600 rounded-full"
                    ></span>
                    Task Manager
                </h1>
                <span class="text-sm text-gray-500">Manage tasks </span>
            </div>

            <!-- Create Project Form -->
            <div class="mb-6">
                <form @submit.prevent="createProject" class="flex gap-2">
                    <input
                        v-model="newProject"
                        type="text"
                        placeholder="Enter new project..."
                        class="border border-gray-200 rounded-lg p-3 flex-1 focus:ring-2 focus:ring-green-400 focus:outline-none shadow-sm"
                        required
                    />
                    <button
                        type="submit"
                        class="bg-green-500 hover:bg-green-600 transition text-white px-5 py-3 rounded-lg shadow font-medium"
                    >
                        + Add Project
                    </button>
                </form>

                <!-- information -->
                <p class="text-gray-500 text-sm py-2">
                    Optionally, organize your tasks into projects to keep track
                    of ongoing work more efficiently.
                </p>
            </div>

            <!-- Task Form -->
            <form
                @submit.prevent="createTask"
                class="flex flex-col md:flex-row gap-3 mb-8"
            >
                <input
                    v-model="newTask"
                    type="text"
                    placeholder="Enter new task..."
                    class="border border-gray-200 rounded-lg p-3 flex-1 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm"
                    required
                />

                <!-- every projet title created shows in this dropdown -->
                <select
                    v-model="selectedProject"
                    class="border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm"
                >
                    <option value="">No Project</option>
                    <option
                        v-for="project in projects"
                        :key="project.id"
                        :value="project.id"
                    >
                        {{ project.title }}
                    </option>
                </select>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-3 rounded-lg shadow font-medium"
                >
                    + Add Task
                </button>
            </form>

            <TaskList
                :projects-with-tasks="projectsWithTasks"
                :tasks-without-project="tasksWithoutProject"
                :tasks="tasks"
                :delete-task="deleteTask"
                :reorder-tasks="reorderTasks"
                :reorder-tasks-project="reorderTasksProject"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import TaskList from "./task_list.vue";

const tasks = ref([]);
const projects = ref([]);
const newTask = ref("");
const selectedProject = ref("");
const newProject = ref("");

// Fetch all tasks
const getAllTask = async () => {
    const res = await axios.get("/api/tasks/all");
    tasks.value = res.data.tasks;
};

// Fetch all projects
const getAllProjects = async () => {
    const res = await axios.get("/api/projects/all");
    projects.value = res.data.projects;
};

const createProject = async () => {
    if (!newProject.value) return;

    const res = await axios.post("/api/projects/create", {
        title: newProject.value,
    });

    // Update projects list
    await getAllProjects();
    newProject.value = "";
};

// Group tasks by project
const projectsWithTasks = computed(() => {
    return projects.value.map((project) => {
        return {
            ...project,
            tasks: tasks.value.filter((task) => task.project_id === project.id),
        };
    });
});

// Tasks without a project
const tasksWithoutProject = computed(() => {
    return tasks.value.filter((task) => !task.project_id);
});

// Create a task
const createTask = async () => {
    await axios.post("/api/tasks/create", {
        title: newTask.value,
        project_id: selectedProject.value || null,
    });
    await getAllTask();
    newTask.value = "";
};

// Delete a task
const deleteTask = async (id) => {
    await axios.delete(`/api/tasks/delete/${id}`);
    tasks.value = tasks.value.filter((task) => task.id !== id);
};

// Reorder tasks (for unprojected tasks)
const reorderTasks = async (taskIds) => {
    await axios.post("/api/tasks/reorder", { tasks: taskIds });
};

const reorderTasksProject = async (projectId, taskIds) => {
    await axios.post("/api/tasks/reorder", { tasks: taskIds });
};

onMounted(() => {
    getAllTask();
    getAllProjects();
});
</script>
