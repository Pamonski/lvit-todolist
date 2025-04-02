<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    todos: Array,
});

// Form helper for adding new todos
const addForm = useForm({
    title: '',
});

// State for inline editing
const editingTodoId = ref(null);
const editForm = useForm({
    title: '',
});

const submitAddForm = () => {
    addForm.post(route('todos.store'), {
        onSuccess: () => addForm.reset(),
    });
};

const toggleComplete = (todo) => {
    router.patch(route('todos.update', todo.id), {
        completed: !todo.completed
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const deleteTodo = (todo) => {
    if (confirm('Are you sure you want to delete this todo?')) {
        router.delete(route('todos.destroy', todo.id), {
            preserveState: true,
            preserveScroll: true,
        });
    }
};

// --- Edit Functions ---
const startEditing = (todo) => {
    editingTodoId.value = todo.id;
    editForm.title = todo.title;
};

const cancelEditing = () => {
    editingTodoId.value = null;
    editForm.reset();
};

const saveEdit = (todo) => {
    editForm.patch(route('todos.update', todo.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            cancelEditing();
        },
        onError: (errors) => {
            console.error("Edit failed:", errors);
        }
    });
};

</script>

<template>
    <Head title="My To-do List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My To-do List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <!-- Add To-do list Form -->
                        <form @submit.prevent="submitAddForm" class="flex space-x-2 mb-6">
                            <TextInput
                                id="title"
                                type="text"
                                class="block w-full"
                                v-model="addForm.title"
                                placeholder="What needs to be done?"
                                required
                                autofocus
                            />
                            <PrimaryButton :class="{ 'opacity-25': addForm.processing }" :disabled="addForm.processing">
                                Add
                            </PrimaryButton>
                        </form>
                         <InputError class="mt-2" :message="addForm.errors.title" />

                        <!-- To-do List -->
                        <div class="mt-4 space-y-3">
                            <div v-if="todos.length === 0" class="text-gray-500 dark:text-gray-400">
                                No to-do lists yet! Add one above.
                            </div>
                            <div
                                v-for="todo in todos"
                                :key="todo.id"
                                class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150 ease-in-out"
                            >
                                <!-- Display Mode -->
                                <div v-if="editingTodoId !== todo.id" class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3 flex-grow min-w-0">
                                        <input
                                            type="checkbox"
                                            :checked="todo.completed"
                                            @change="toggleComplete(todo)"
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800 flex-shrink-0"
                                        />
                                        <span
                                            :class="{ 'line-through text-gray-500 dark:text-gray-400': todo.completed }"
                                            class="text-gray-800 dark:text-gray-200 break-words truncate"
                                            @dblclick="startEditing(todo)"
                                        >
                                            {{ todo.title }}
                                        </span>
                                    </div>

                                    <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                                        <button
                                            @click="startEditing(todo)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium"
                                            title="Edit"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteTodo(todo)"
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium"
                                            title="Delete"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <!-- Edit Mode -->
                                 <div v-else class="space-y-2">
                                    <form @submit.prevent="saveEdit(todo)" class="flex items-center space-x-2">
                                        <TextInput
                                            type="text"
                                            v-model="editForm.title"
                                            class="block w-full text-sm"
                                            required
                                            @keyup.esc="cancelEditing"
                                            ref="editInputRef"
                                        />
                                         <PrimaryButton
                                            type="submit"
                                            :class="{ 'opacity-25': editForm.processing }"
                                            :disabled="editForm.processing || !editForm.isDirty"
                                         >
                                            Save
                                         </PrimaryButton>
                                         <SecondaryButton type="button" @click="cancelEditing">
                                            Cancel
                                         </SecondaryButton>
                                    </form>
                                    <InputError class="mt-1 text-xs" :message="editForm.errors.title" />
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>