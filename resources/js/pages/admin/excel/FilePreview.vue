<script setup lang="ts">
import { Head as InertiaHead, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import { dashboard } from '@/routes';

type ExcelPreviewRow = {
    id: number;
    row_data: Array<string | number | null>;
    created_at: string;
};

const page = usePage();
const excelName = computed(() => String(page.props.excelName ?? ''));

const rows = ref<ExcelPreviewRow[]>([]);
const loading = ref(true);
const current = ref(1);
const pageSize = ref(100);
const total = ref(0);
const errorMessage = ref('');

const maxColumns = computed(() => {
    return rows.value.reduce((max, row) => Math.max(max, row.row_data.length), 0);
});

const columnIndexes = computed(() => {
    return Array.from({ length: maxColumns.value }, (_, index) => index);
});

const breadcrumbs = computed(() => [
    {
        title: 'Home',
        href: dashboard(),
    },
    {
        title: 'Archivos Excel',
        href: admin.excel.files(),
    },
    {
        title: excelName.value || 'Vista de archivo',
        href: '#',
    },
]);

const loadRows = async () => {
    if (!excelName.value) {
        errorMessage.value = 'No se recibió el nombre del archivo.';
        loading.value = false;
        return;
    }

    loading.value = true;
    errorMessage.value = '';

    try {
        const response = await axios.get('/admin/excel/file-preview/rows', {
            params: {
                excel_name: excelName.value,
                current: current.value,
                pageSize: pageSize.value,
            },
        });

        if (response.data?.success) {
            rows.value = response.data.data.rows?.data ?? [];
            total.value = Number(response.data.data.rows?.total ?? 0);
            current.value = Number(response.data.data.rows?.current_page ?? current.value);
            pageSize.value = Number(response.data.data.rows?.per_page ?? pageSize.value);
        } else {
            errorMessage.value = 'No se pudo cargar el contenido del archivo.';
        }
    } catch (error) {
        console.error('Error al cargar contenido del archivo:', error);
        errorMessage.value = 'Error al cargar el contenido del archivo.';
    } finally {
        loading.value = false;
    }
};

const goBack = () => {
    window.location.href = admin.excel.files().url;
};

const onShowSizeChange = async (newCurrent: number, newPageSize: number) => {
    current.value = newCurrent;
    pageSize.value = newPageSize;
    await loadRows();
};

const onChangePage = async (newCurrent: number) => {
    current.value = newCurrent;
    await loadRows();
};

onMounted(async () => {
    await loadRows();
});
</script>

<template>
    <InertiaHead :title="`Contenido: ${excelName || 'Archivo'}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Contenido del archivo</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ excelName }}</p>
                </div>
                <button
                    type="button"
                    class="rounded-md bg-slate-700 px-3 py-2 text-xs font-medium text-white hover:bg-slate-800"
                    @click="goBack"
                >
                    Volver a archivos
                </button>
            </div>

            <p v-if="errorMessage" class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                {{ errorMessage }}
            </p>

            <div v-if="loading" class="py-10 text-center text-sm text-slate-600 dark:text-slate-300">
                Cargando contenido...
            </div>

            <div v-else-if="!rows.length" class="py-10 text-center text-sm text-slate-600 dark:text-slate-300">
                Este archivo no tiene filas registradas.
            </div>

            <div v-else class="overflow-x-auto rounded-lg border border-slate-200 dark:border-slate-700">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th class="whitespace-nowrap px-3 py-2 text-left text-xs font-semibold text-slate-700 dark:text-slate-200">#</th>
                            <th
                                v-for="columnIndex in columnIndexes"
                                :key="`head-${columnIndex}`"
                                class="whitespace-nowrap px-3 py-2 text-left text-xs font-semibold text-slate-700 dark:text-slate-200"
                            >
                                Columna {{ columnIndex + 1 }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <tr v-for="(row, rowIndex) in rows" :key="row.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-900/30">
                            <td class="whitespace-nowrap px-3 py-2 text-xs text-slate-500 dark:text-slate-300">
                                {{ (current - 1) * pageSize + rowIndex + 1 }}
                            </td>
                            <td
                                v-for="columnIndex in columnIndexes"
                                :key="`row-${row.id}-${columnIndex}`"
                                class="whitespace-nowrap px-3 py-2 text-xs text-slate-700 dark:text-slate-200"
                            >
                                {{ row.row_data[columnIndex] ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="total > 0" class="flex justify-center pt-2">
                <a-pagination
                    :show-total="(rangeTotal: number, range: number[]) => `${range[0]} a ${range[1]} de ${rangeTotal} filas`"
                    :pageSizeOptions="['50', '100', '200', '500']"
                    v-model:current="current"
                    v-model:page-size="pageSize"
                    :total="total"
                    show-size-changer
                    @showSizeChange="onShowSizeChange"
                    @change="onChangePage"
                />
            </div>
        </div>
    </AppLayout>
</template>
