<template>
    <InertiaHead title="Excel" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="relative min-h-[calc(100vh-8rem)] overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 px-6 py-10 text-slate-50"
        >
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(56,189,248,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(129,140,248,0.16),transparent_35%),radial-gradient(circle_at_60%_70%,rgba(16,185,129,0.12),transparent_30%)]"
            />

            <div class="relative mx-auto flex max-w-5xl flex-col gap-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-cyan-200/80">
                            Rusconi · Ingesta de datos
                        </p>
                        <h1 class="mt-2 text-3xl font-bold text-white lg:text-4xl">
                            {{ canCreate ? 'Subir y procesar archivo Excel' : 'Consultar archivos Excel' }}
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-300/90">
                            {{ canCreate ? 'Valida el archivo antes de subirlo. Aceptamos .xlsx, .xls y .csv hasta 20MB.' : 'Solo tienes permisos de lectura. Contacta al administrador para subir archivos.' }}
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-slate-200 shadow-lg shadow-cyan-500/10 backdrop-blur">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(16,185,129,0.2)]" />
                            Operativo
                        </div>
                        <p class="mt-1 text-[11px] text-slate-300/80">
                            {{ canCreate ? 'Conectado al orquestador de ingesta.' : 'Modo solo lectura' }}
                        </p>
                    </div>
                </div>

                <div
                    v-if="canCreate"
                    class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-cyan-500/15 backdrop-blur"
                >
                    <div class="border-b border-white/5 bg-white/5 px-6 py-4">
                        <p class="text-sm font-semibold text-white">Carga y validación</p>
                        <p class="text-xs text-slate-300/80">
                            Selecciona el archivo y ejecuta el proceso de validación.
                        </p>
                    </div>

                    <form @submit.prevent="uploadFile" class="space-y-6 px-6 py-6">
                        <!-- Toggle Lista Negra/PEP -->
                        <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-slate-900/40 px-5 py-4">
                            <div>
                                <p class="text-sm font-semibold text-white">Subir como Lista Negra / PEP</p>
                                <p class="text-xs text-slate-300/80 mt-1">
                                    Activa esta opción si el archivo contiene nombres de personas en listas negras o personas políticamente expuestas.
                                </p>
                            </div>
                            <button
                                type="button"
                                @click="esListaNegra = !esListaNegra"
                                :class="[
                                    'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none',
                                    esListaNegra ? 'bg-rose-500' : 'bg-slate-600'
                                ]"
                            >
                                <span
                                    :class="[
                                        'inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out',
                                        esListaNegra ? 'translate-x-5' : 'translate-x-0'
                                    ]"
                                />
                            </button>
                        </div>

                        <div
                            v-if="esListaNegra"
                            class="rounded-2xl border border-rose-400/30 bg-rose-400/10 px-4 py-3 text-sm text-rose-100"
                        >
                            <p class="font-semibold">⚠ Modo Lista Negra / PEP activo</p>
                            <p class="text-xs mt-1 text-rose-200/80">
                                Los nombres de este archivo se cruzarán automáticamente contra los archivos de datos subidos. Se generarán alertas por cada coincidencia exacta.
                            </p>
                        </div>

                        <div class="grid gap-4 rounded-2xl border border-dashed border-white/15 bg-slate-900/40 p-5">
                            <label for="file" class="text-sm font-semibold text-white">
                                Seleccionar archivo Excel
                            </label>
                            <div class="relative">
                                <input
                                    id="file"
                                    type="file"
                                    ref="fileInput"
                                    @change="handleFileChange"
                                    accept=".xlsx,.xls,.csv"
                                    class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                                    :disabled="uploading"
                                />
                                <button
                                    type="button"
                                    @click="($refs.fileInput as HTMLInputElement)?.click()"
                                    :disabled="uploading"
                                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white ring-1 ring-white/15 transition hover:bg-white/20 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        class="h-4 w-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4v12m0 0l3.5-3.5M12 16L8.5 12.5M4 19h16"
                                        />
                                    </svg>
                                    Seleccionar archivo
                                </button>
                            </div>
                            <p class="text-xs text-slate-300/80">
                                Formatos permitidos: .xlsx, .xls, .csv (máx. 20MB)
                            </p>
                        </div>

                        <div
                            v-if="selectedFile"
                            class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100"
                        >
                            <p class="font-semibold text-white">
                                Archivo seleccionado: {{ selectedFile.name }}
                            </p>
                            <p class="text-xs text-emerald-100/80">
                                Tamaño: {{ formatFileSize(selectedFile.size) }}
                            </p>
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

                        <button
                            type="submit"
                            :disabled="!selectedFile || uploading"
                            class="group relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-400 via-cyan-400 to-indigo-500 px-5 py-3 text-sm font-semibold text-slate-900 shadow-xl shadow-emerald-400/25 transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span
                                class="absolute inset-0 translate-y-[120%] bg-white/20 transition group-hover:translate-y-0"
                                aria-hidden="true"
                            />
                            <span class="relative flex items-center gap-2">
                                <svg
                                    v-if="uploading"
                                    class="h-5 w-5 animate-spin text-slate-900"
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
                                <span class="relative">
                                    {{ uploading ? 'Procesando...' : 'Subir y procesar archivo' }}
                                </span>
                            </span>
                        </button>
                    </form>
                </div>

                <div
                    v-else
                    class="overflow-hidden rounded-3xl border border-amber-400/20 bg-amber-400/10 shadow-2xl shadow-amber-500/15 backdrop-blur"
                >
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-white">Sin permisos de carga</h3>
                        <p class="mt-2 text-sm text-slate-300">
                            Solo tienes permisos para consultar y buscar datos en los archivos Excel existentes. <br>
                            Contacta al administrador si necesitas subir nuevos archivos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script lang="ts">
import { Head as InertiaHead, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import { dashboard } from '@/routes';
import axios from 'axios';

export default {
    components: {
        InertiaHead,
        AppLayout
    },
    data() {
        return {
            breadcrumbs: [
                {
                    title: 'Home',
                    href: dashboard().url
                },
                {
                    title: 'Excel',
                    href: admin.excel.index().url
                }
            ],
            selectedFile: null as File | null,
            uploading: false,
            esListaNegra: false,
            message: null as string | null,
            messageType: null as string | null
        }
    },
    computed: {
        canCreate() {
            const page = usePage();
            const permissions = page.props.auth?.user?.permissions || [];
            return permissions.includes('Excel create') || page.props.auth?.user?.roles?.includes('root');
        },
        canDelete() {
            const page = usePage();
            const permissions = page.props.auth?.user?.permissions || [];
            return permissions.includes('Excel delete') || page.props.auth?.user?.roles?.includes('root');
        }
    },
    methods: {
        handleFileChange(event: Event) {
            if (!this.canCreate) {
                this.showMessage('No tienes permisos para subir archivos', 'error');
                return;
            }
            const target = event.target as HTMLInputElement;
            const file = target.files?.[0];
            if (file) {
                if (file.size > 20 * 1024 * 1024) {
                    this.showMessage('El archivo es demasiado grande. Máximo 20MB.', 'error');
                    target.value = '';
                    return;
                }
                const validExtensions = ['.xlsx', '.xls', '.csv'];
                const fileExtension = '.' + file.name.split('.').pop()!.toLowerCase();
                if (!validExtensions.includes(fileExtension)) {
                    this.showMessage('Formato de archivo no válido. Use .xlsx, .xls o .csv', 'error');
                    target.value = '';
                    return;
                }
                this.selectedFile = file;
                this.message = null;
            }
        },
        async uploadFile() {
            if (!this.canCreate) {
                this.showMessage('No tienes permisos para subir archivos', 'error');
                return;
            }

            this.message = null;
            if (!this.selectedFile) {
                this.showMessage('Por favor seleccione un archivo', 'error');
                return;
            }

            this.uploading = true;
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('tipo', this.esListaNegra ? 'lista_negra' : 'datos');

            // Leer XSRF-TOKEN completo (el valor puede contener '=' por ser Base64)
            const xsrfCookie = document.cookie
                .split('; ')
                .find(row => row.startsWith('XSRF-TOKEN='));
            const xsrfToken = xsrfCookie
                ? decodeURIComponent(xsrfCookie.substring('XSRF-TOKEN='.length))
                : '';

            try {
                const response = await axios.post(admin.excel.upload.url(), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-XSRF-TOKEN': xsrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    withCredentials: true,
                    timeout: 300000,
                });
                console.log('✅ Respuesta del servidor:', response.data);
                if (response.data?.success === true) {
                    const alertas = response.data?.data?.alertas_generadas ?? 0;
                    let msg = 'Archivo procesado exitosamente.';
                    if (alertas > 0) {
                        msg += ` ⚠ Se generaron ${alertas} alerta(s) en Lista Negra/PEP. Revísalas en Alertas.`;
                    }
                    console.log('✅ Mostrando mensaje de éxito:', msg);
                    this.showMessage(msg, 'success');
                    this.selectedFile = null;
                    this.esListaNegra = false;
                    if (this.$refs.fileInput) {
                        (this.$refs.fileInput as HTMLInputElement).value = '';
                    }
                } else {
                    console.error('❌ Success no es true:', response.data);
                    this.showMessage('Error inesperado al procesar el archivo', 'error');
                }
            } catch (error: any) {
                console.error('Error completo:', error);
                let errorMessage = 'Error al procesar el archivo';
                if (error.response?.status === 403) {
                    errorMessage = 'No tienes permisos para realizar esta acción';
                } else if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(' ') as string;
                } else if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }
                this.showMessage(errorMessage, 'error');
            } finally {
                this.uploading = false;
            }
        },
        showMessage(msg: string, type: string) {
            console.log('📢 showMessage llamado:', { msg, type });
            this.message = msg;
            this.messageType = type;
            // Mantener el mensaje por 10 segundos para mensajes de éxito
            if (type === 'success') {
                setTimeout(() => {
                    if (this.message === msg) {
                        this.message = null;
                        this.messageType = null;
                    }
                }, 10000);
            }
        },
        formatFileSize(bytes: number): string {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
};
</script>
