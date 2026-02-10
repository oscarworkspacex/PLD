<template>
    <Head title="Archivos Excel" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="relative min-h-[calc(100vh-8rem)] overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 px-6 py-10 text-slate-50"
        >
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(56,189,248,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(129,140,248,0.16),transparent_35%),radial-gradient(circle_at_60%_70%,rgba(16,185,129,0.12),transparent_30%)]"
            />

            <div class="relative mx-auto flex max-w-6xl flex-col gap-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-cyan-200/80">
                            Rusconi · Gestión de archivos
                        </p>
                        <h1 class="mt-2 text-3xl font-bold text-white lg:text-4xl">
                            Archivos Excel subidos
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-300/90">
                            Administra los archivos Excel que han sido procesados. Puedes ver detalles y eliminar archivos.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-slate-200 shadow-lg shadow-cyan-500/10 backdrop-blur">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(16,185,129,0.2)]" />
                            {{ files.length }} archivo{{ files.length !== 1 ? 's' : '' }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-300/80">
                            Total de archivos procesados
                        </p>
                    </div>
                </div>

                <div
                    v-if="message"
                    :class="[
                        'rounded-2xl px-4 py-3 text-sm font-semibold',
                        messageType === 'success'
                            ? 'border border-emerald-400/30 bg-emerald-400/10 text-emerald-100'
                            : 'border border-rose-400/30 bg-rose-400/10 text-rose-50'
                    ]"
                >
                    {{ message }}
                </div>

                <div
                    class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-cyan-500/15 backdrop-blur"
                >
                    <div class="border-b border-white/5 bg-white/5 px-6 py-4">
                        <p class="text-sm font-semibold text-white">Lista de archivos</p>
                        <p class="text-xs text-slate-300/80">
                            Archivos Excel procesados en el sistema
                        </p>
                    </div>

                    <div v-if="loading" class="px-6 py-12 text-center">
                        <div class="inline-flex items-center gap-3 text-slate-300">
                            <svg
                                class="h-6 w-6 animate-spin"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-30"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                />
                                <path
                                    class="opacity-80"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                />
                            </svg>
                            <span>Cargando archivos...</span>
                        </div>
                    </div>

                    <div v-else-if="files.length === 0" class="px-6 py-12 text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-800/50">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="h-8 w-8 text-slate-400"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                />
                            </svg>
                        </div>
                        <p class="text-lg font-semibold text-white">No hay archivos</p>
                        <p class="mt-1 text-sm text-slate-300/80">
                            Aún no se han subido archivos Excel al sistema
                        </p>
                    </div>

                    <div v-else class="divide-y divide-white/5">
                        <div
                            v-for="file in files"
                            :key="file.excel_name"
                            class="group px-6 py-4 transition hover:bg-white/5"
                        >
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400/20 to-cyan-400/20 ring-1 ring-white/10">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="h-6 w-6 text-emerald-300"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-white">{{ file.excel_name }}</p>
                                        <p class="text-xs text-slate-300/80">
                                            {{ file.records_count }} registro{{ file.records_count !== 1 ? 's' : '' }} · 
                                            Subido {{ formatDate(file.created_at) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="confirmDelete(file.excel_name)"
                                        :disabled="deleting === file.excel_name"
                                        class="rounded-xl bg-rose-500/10 px-4 py-2 text-sm font-semibold text-rose-300 ring-1 ring-rose-500/20 transition hover:bg-rose-500/20 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        <span v-if="deleting === file.excel_name" class="flex items-center gap-2">
                                            <svg
                                                class="h-4 w-4 animate-spin"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    class="opacity-30"
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                />
                                                <path
                                                    class="opacity-80"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                />
                                            </svg>
                                            Eliminando...
                                        </span>
                                        <span v-else>Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            @click.self="showDeleteModal = false"
        >
            <div class="mx-4 w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-slate-900 shadow-2xl">
                <div class="border-b border-white/5 bg-white/5 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Confirmar eliminación</h3>
                </div>
                <div class="px-6 py-6">
                    <p class="text-sm text-slate-300">
                        ¿Estás seguro de que deseas eliminar el archivo
                        <span class="font-semibold text-white">{{ fileToDelete }}</span>?
                    </p>
                    <p class="mt-2 text-xs text-rose-300/80">
                        Esta acción eliminará todos los registros asociados a este archivo y no se puede deshacer.
                    </p>
                </div>
                <div class="flex gap-3 border-t border-white/5 bg-white/5 px-6 py-4">
                    <button
                        @click="showDeleteModal = false"
                        class="flex-1 rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/15 transition hover:bg-white/20"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="deleteFile"
                        class="flex-1 rounded-xl bg-rose-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-600"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import { dashboard } from '@/routes';
import axios from 'axios';

export default {
    components: {
        Head,
        AppLayout
    },
    data() {
        return {
            breadcrumbs: [
                {
                    title: 'Home',
                    href: dashboard()
                },
                {
                    title: 'Archivos Excel',
                    href: admin.excel.files()
                }
            ],
            files: [],
            loading: true,
            deleting: null,
            message: null,
            messageType: null,
            showDeleteModal: false,
            fileToDelete: null
        }
    },
    mounted() {
        this.loadFiles();
    },
    methods: {
        async loadFiles() {
            this.loading = true;
            try {
                const response = await axios.get(admin.excel.list.url());
                if (response.data.success) {
                    this.files = response.data.data;
                }
            } catch (error) {
                console.error('Error al cargar archivos:', error);
                this.showMessage('Error al cargar los archivos', 'error');
            } finally {
                this.loading = false;
            }
        },
        confirmDelete(excelName) {
            this.fileToDelete = excelName;
            this.showDeleteModal = true;
        },
        async deleteFile() {
            if (!this.fileToDelete) return;
            
            this.deleting = this.fileToDelete;
            this.showDeleteModal = false;
            
            try {
                const response = await axios.delete(admin.excel.delete.url(), {
                    data: { excel_name: this.fileToDelete }
                });
                
                if (response.data.success) {
                    this.showMessage('Archivo eliminado correctamente', 'success');
                    this.files = this.files.filter(f => f.excel_name !== this.fileToDelete);
                } else {
                    this.showMessage(response.data.message || 'Error al eliminar el archivo', 'error');
                }
            } catch (error) {
                console.error('Error al eliminar archivo:', error);
                this.showMessage(
                    error.response?.data?.message || 'Error al eliminar el archivo',
                    'error'
                );
            } finally {
                this.deleting = null;
                this.fileToDelete = null;
            }
        },
        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
            setTimeout(() => {
                this.message = null;
                this.messageType = null;
            }, 5000);
        },
        formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays === 0) {
                return 'hoy';
            } else if (diffDays === 1) {
                return 'ayer';
            } else if (diffDays < 7) {
                return `hace ${diffDays} días`;
            } else {
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }
        }
    }
}
</script>
