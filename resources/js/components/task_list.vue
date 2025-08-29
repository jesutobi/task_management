<template>
    <!-- Task List -->
    <div>
        <!--
            This section lets the user switch between viewing "Projects" or "Tasks".
                - switchMode controls which view is active.
                - The buttons change switchMode when clicked.
                - The active button is highlighted with a blue background, while the inactive one is gray.
                -->
        <div class="flex justify-center">
            <div class="flex items-center mb-6 space-x-4">
                <button
                    :class="
                        switchMode === 'projects'
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700'
                    "
                    class="px-4 py-2 rounded-md font-semibold"
                    @click="switchMode = 'projects'"
                >
                    Projects
                </button>
                <button
                    :class="
                        switchMode === 'tasks'
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-200 text-gray-700'
                    "
                    class="px-4 py-2 rounded-md font-semibold"
                    @click="switchMode = 'tasks'"
                >
                    Tasks
                </button>
            </div>
        </div>

        <!-- Projects Mode -->
        <div v-if="switchMode === 'projects'">
            <div
                v-for="project in ProjectsWithTasks"
                :key="project.id"
                class="mb-6"
            >
                <div v-if="project.tasks && project.tasks.length">
                    <h5
                        class="text-md font-semibold text-blue-600 text-2xl mb-2"
                    >
                        {{ project.title }}
                    </h5>

                    <draggable
                        v-model="project.tasks"
                        @end="
                            () => reorderTasksProject(project.id, project.tasks)
                        "
                        item-key="id"
                    >
                        <template #item="{ element }">
                            <li
                                class="flex justify-between items-center p-4 mb-2 bg-gray-50 hover:bg-gray-100 transition border rounded-xl shadow-sm"
                            >
                                <div>
                                    <p class="text-gray-800 font-medium">
                                        {{ element.title }}
                                    </p>
                                    <p class="text-gray-500 text-sm py-2">
                                        {{ formatDate(element.created_at) }}
                                    </p>
                                </div>
                                <button
                                    @click="deleteTask(element.id)"
                                    class="bg-red-500 hover:bg-red-600 transition text-white px-3 py-1.5 rounded-md text-sm font-medium"
                                >
                                    Delete
                                </button>
                            </li>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>

        <!-- Tasks Mode -->
        <div v-if="switchMode === 'tasks'">
            <div v-if="TasksWithoutProject.length">
                <p class="text-gray-500 text-sm py-2">
                    These are tasks not organised in a project
                </p>
                <draggable
                    v-model="TasksWithoutProject"
                    @end="() => reorderTasks(TasksWithoutProject)"
                    item-key="id"
                >
                    <template #item="{ element }">
                        <li
                            class="flex justify-between items-center p-4 mb-2 bg-gray-50 hover:bg-gray-100 transition border rounded-xl shadow-sm"
                        >
                            <div>
                                <p class="text-gray-800 font-medium">
                                    {{ element.title }}
                                </p>
                                <p class="text-gray-500 text-sm py-2">
                                    {{ formatDate(element.created_at) }}
                                </p>
                            </div>
                            <button
                                @click="deleteTask(element.id)"
                                class="bg-red-500 hover:bg-red-600 transition text-white px-3 py-1.5 rounded-md text-sm font-medium"
                            >
                                Delete
                            </button>
                        </li>
                    </template>
                </draggable>
            </div>
        </div>

        <p v-if="!tasks.length" class="text-gray-400 text-center mt-6">
            No tasks yet. Add your first task
        </p>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import draggable from "vuedraggable";

// Props from parent
const props = defineProps({
    projectsWithTasks: Array,
    tasksWithoutProject: Array,
    tasks: Array,
    deleteTask: Function,
    reorderTasks: Function,
    reorderTasksProject: Function,
});

// this helps to format date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

// This controls whether we show projects or tasks
const switchMode = ref("projects");

// i made local copies of props so we can use v-model with draggable
// Props are read-only, so we need writable copies for drag-and-drop
const ProjectsWithTasks = ref(
    JSON.parse(JSON.stringify(props.projectsWithTasks))
);
const TasksWithoutProject = ref([...props.tasksWithoutProject]);

// Notify parent about reordered tasks
const reorderTasksProject = (projectId, tasksArray) => {
    const order = tasksArray.map((task) => task.id);
    props.reorderTasksProject(projectId, order);
};

const reorderTasks = (tasksArray) => {
    const order = tasksArray.map((task) => task.id);
    props.reorderTasks(order);
};

// This watches for changes from the parent and update our local copies
watch(
    () => props.projectsWithTasks,
    (newVal) => {
        ProjectsWithTasks.value = JSON.parse(JSON.stringify(newVal));
    },
    { deep: true }
);

watch(
    () => props.tasksWithoutProject,
    (newVal) => {
        TasksWithoutProject.value = [...newVal];
    }
);
</script>
