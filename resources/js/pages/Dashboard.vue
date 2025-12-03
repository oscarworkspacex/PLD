<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { ref } from 'vue';
import axios from 'axios';
import admin from '@/routes/admin';

// Reactive state for the search
const searchQuery = ref('');
const data = ref([]);
const current = ref(1);
const total = ref(0);
const pageSize = ref(50);

// Search function
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        const value = searchQuery.value;
        axios.get(admin.excel.search.data.url(), {
            params: {
                value: value,
                current: current.value,
                pageSize: pageSize.value
            }
        }).then(response => {
            data.value = response.data.data['data'];
            total.value = response.data.data['total'];
            current.value = response.data.data['current_page'];
            pageSize.value = response.data.data['per_page'];
        }).catch(error => {
            console.log(error);
        });
    }
};

const onShowSizeChange = (newCurrent: number, newPageSize: number) => {
    pageSize.value = newPageSize;
    current.value = newCurrent;
    handleSearch();
}

const onChangePage = (newCurrent: number) => {
    current.value = newCurrent;
    handleSearch();
}

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
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
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
                        <input v-model="searchQuery" type="text" placeholder="Buscar..." @keydown.enter="handleSearch"
                            class="w-full rounded-full border border-gray-300 bg-white px-6 py-4 pr-16 text-lg shadow-lg transition-all duration-200 ease-in-out focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-400 dark:focus:ring-blue-400/20" />
                        <button type="button" @click="handleSearch"
                            class="absolute right-2 flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white transition-all duration-200 ease-in-out hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:bg-blue-400 dark:hover:bg-blue-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Optional: Add a subtle shadow/glow effect -->
                    <div
                        class="absolute inset-0 -z-10 rounded-full bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 blur-xl dark:from-blue-400/20 dark:via-purple-400/20 dark:to-pink-400/20">
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="card mt-3 px-0">
                    <div class="card-body p-0">
                        <a-table :scroll="{ x: true }" :dataSource="data"
                            :locale="{ emptyText: 'Sin datos' }" :pagination="false" bordered class="ant-table-striped"
                            :row-class-name="(_record, index) => index % 2 === 1 ? 'table-striped' : null">
                            <a-table-column key="id" title="ID" data-index="id" :sorter="false"
                                :showSorterTooltip="false">
                                <template #default="{ record }">
                                    <span>
                                        {{ record.id }}
                                    </span>
                                </template>
                            </a-table-column>

                            <a-table-column key="value" title="Dato" data-index="value" :sorter="false"
                                :showSorterTooltip="false">
                                <template #default="{ record }">
                                    <span>
                                        {{ record.value }}
                                    </span>
                                </template>
                            </a-table-column>

                            <a-table-column key="file" title="Archivo" data-index="file" :sorter="false"
                                :showSorterTooltip="false">
                                <template #default="{ record }">
                                    <span>
                                        {{ record.excel_name }}
                                    </span>
                                </template>
                            </a-table-column>



                        </a-table>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center mt-5">
                                <a-pagination :show-total="(total, range) =>
                                        `${range[0]} a ${range[1]} de ${numeralFormat(total, '0,0')} resultados`
                                    " :pageSizeOptions="['10', '20', '50', '100']" v-model:current="current"
                                    v-model:page-size="pageSize" :total="total" show-size-changer
                                    @showSizeChange="onShowSizeChange" @change="onChangePage">
                                    <template #buildOptionText="props">
                                        <span>{{ props.value }} / Página</span>
                                    </template>
                                </a-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
