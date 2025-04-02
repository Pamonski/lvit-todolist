<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="relative min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-md w-full p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg text-center">

             <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                Welcome to the To-do List App!
             </h1>

            <div v-if="canLogin" class="flex items-center justify-center space-x-4">
                 <Link
                    v-if="$page.props.auth.user"
                    :href="route('todos.index')"
                    class="rounded-md px-4 py-2 bg-indigo-600 text-white ring-1 ring-transparent transition hover:bg-indigo-700 focus:outline-none focus-visible:ring-[#FF2D20] dark:focus-visible:ring-white"
                >
                    Go to To-do List
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-md px-4 py-2 text-black dark:text-white ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-md px-4 py-2 bg-gray-600 text-white ring-1 ring-transparent transition hover:bg-gray-700 focus:outline-none focus-visible:ring-[#FF2D20] dark:bg-gray-500 dark:hover:bg-gray-400 dark:focus-visible:ring-white"
                    >
                        Register
                    </Link>
                </template>
            </div>
            <p v-else class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                (Login/Register not enabled)
            </p>
        </div>
    </div>
</template>

<style>
</style>