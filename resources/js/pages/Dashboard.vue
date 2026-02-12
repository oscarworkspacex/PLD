<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import axios from 'axios';
import admin from '@/routes/admin';
import { numeralFormat } from 'vue-numerals';

// Reactive state for the search
const searchQuery = ref('');
const data = ref([]);
const current = ref(1);
const total = ref(0);
const pageSize = ref(50);
const page = usePage();
const selectedCaptureOption = ref('solicitud-p-fisica');
const captureOptions = [
    { label: 'Solicitud P. Física', value: 'solicitud-p-fisica' },
    { label: 'Solicitud P. Física A.E', value: 'solicitud-p-fisica-ae' },
    { label: 'Solicitud P. Moral', value: 'solicitud-p-moral' },
];

const isCapturaClienteView = computed(() => {
    const [, queryString = ''] = page.url.split('?');
    const params = new URLSearchParams(queryString);
    return params.get('section') === 'captura-cliente';
});

const isSolicitudFisicaSelected = computed(() => selectedCaptureOption.value === 'solicitud-p-fisica');

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
            <template v-if="isCapturaClienteView">
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="mb-4 text-xl font-semibold text-slate-900 dark:text-slate-100">Captura de cliente</h2>

                    <div class="mb-4">
                        <label for="capture-option" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">
                            Tipo de solicitud
                        </label>
                        <select
                            id="capture-option"
                            v-model="selectedCaptureOption"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option v-for="option in captureOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                    </div>

                    <div class="grid gap-4 md:grid-cols-[260px_1fr]">
                        <aside class="rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-900/40">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Opciones
                            </p>
                            <div class="space-y-2">
                                <button
                                    v-for="option in captureOptions"
                                    :key="option.value"
                                    type="button"
                                    @click="selectedCaptureOption = option.value"
                                    class="w-full rounded-md px-3 py-2 text-left text-sm transition-colors"
                                    :class="selectedCaptureOption === option.value
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white text-slate-700 hover:bg-slate-100 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-800'"
                                >
                                    {{ option.label }}
                                </button>
                            </div>
                        </aside>

                        <section class="rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950">
                            <div v-if="isSolicitudFisicaSelected">
                                <div class="mb-4 flex flex-wrap gap-2 border-b border-slate-200 pb-3 dark:border-slate-800">
                                    <span class="rounded-md bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Medio de contacto</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Datos de identificación</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Datos laborales</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Solicitud de operación</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Datos de contacto</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">Garantías</span>
                                    <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">P.L.D.</span>
                                </div>

                                <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-800">
                                    <div class="mb-4 rounded-md bg-slate-100 px-3 py-2 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        Para uso exclusivo de la SOFOM
                                    </div>

                                    <div class="grid gap-4 lg:grid-cols-[1fr_250px]">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Medio de contacto <span class="text-red-500">*</span></label>
                                                <select class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900">
                                                    <option>PROMOTOR</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Promotor <span class="text-red-500">*</span></label>
                                                <input type="text" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900" />
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Puesto del contacto</label>
                                                <input type="text" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900" />
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">Teléfono del contacto</label>
                                                <input type="text" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900" />
                                            </div>
                                        </div>

                                        <div class="h-fit rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-700 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-300">
                                            Aviso campos requeridos
                                        </div>
                                    </div>

                                    <div class="mt-6 text-center">
                                        <button
                                            type="button"
                                            class="rounded-md bg-blue-600 px-5 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                                        >
                                            Guardar información
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="rounded-md border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                                Vista disponible próximamente para esta opción.
                            </div>
                        </section>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="mb-8 text-center">
                    <h1 class="title-system">
                        Sistema Automátizado en Prevención de Lavado de Dinero y Financiamiento al Terrorismo
                    </h1>
                </div>

                <div class="mb-6 text-center">
                    <h2 class="text-xl font-semibold text-gray-600 dark:text-gray-300">
                        Buscador de Personas
                    </h2>
                </div>
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
                        <div
                            class="absolute inset-0 -z-10 rounded-full bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 blur-xl dark:from-blue-400/20 dark:via-purple-400/20 dark:to-pink-400/20">
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="card mt-3 px-0">
                        <div class="card-body p-0">
                            <a-table :scroll="{ x: true }" :dataSource="data"
                                :locale="{ emptyText: 'Sin datos' }" :pagination="false" bordered
                                class="ant-table-striped dashboard-jade-table"
                                :row-class-name="(_record: any, index: number) => index % 2 === 1 ? 'table-striped' : null">
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
                                    <a-pagination class="dashboard-jade-pagination" :show-total="(total: number, range: number[]) =>
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
            </template>
        </div>
    </AppLayout>
</template>
