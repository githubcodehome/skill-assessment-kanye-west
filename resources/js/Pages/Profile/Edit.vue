<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Favorites" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Favorites</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            </div>
        </div>
    </AuthenticatedLayout>
</template>
