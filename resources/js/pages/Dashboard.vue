<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from 'vue';

// Reactive state for the search
const searchQuery = ref('');

// Search function
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Here you could implement actual search functionality
        console.log('Searching for:', searchQuery.value);
        // For now, just show an alert or you could dispatch an event, etc.
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <!-- System Title -->
            <div class="mb-8 text-center">
                <h1 class="title-system">
                    Sistema Automátizado en Prevención de Lavado de Dinero y Financiamiento al Terrorismo
                </h1>
            </div>

            <!-- Search Section Title -->
            <div class="mb-6 text-center">
                <h2 class="text-xl font-semibold text-gray-600 dark:text-gray-300">
                    Buscador de Personas
                </h2>
            </div>

            <!-- Professional Search Bar -->
            <div class="mb-6 flex justify-center">
                <div class="relative w-full max-w-2xl">
                    <div class="relative flex items-center">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Buscar..."
                            @keydown.enter="handleSearch"
                            class="w-full rounded-full border border-gray-300 bg-white px-6 py-4 pr-16 text-lg shadow-lg transition-all duration-200 ease-in-out focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400/20"
                        />
                        <button
                            type="button"
                            @click="handleSearch"
                            class="absolute right-2 flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white transition-all duration-200 ease-in-out hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:bg-blue-400 dark:hover:bg-blue-500"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Optional: Add a subtle shadow/glow effect -->
                    <div class="absolute inset-0 -z-10 rounded-full bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 blur-xl dark:from-blue-400/20 dark:via-purple-400/20 dark:to-pink-400/20"></div>
                </div>
            </div>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <PlaceholderPattern />
                </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
