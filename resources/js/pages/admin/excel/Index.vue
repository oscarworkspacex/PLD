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
                            Subir y procesar archivo Excel
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-300/90">
                            Valida el archivo antes de subirlo. Aceptamos .xlsx, .xls y .csv
                            hasta 20MB.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-slate-200 shadow-lg shadow-cyan-500/10 backdrop-blur">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_0_4px_rgba(16,185,129,0.2)]" />
                            Operativo
                        </div>
                        <p class="mt-1 text-[11px] text-slate-300/80">
                            Conectado al orquestador de ingesta.
                        </p>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-3xl border border-white/10 bg-white/5 shadow-2xl shadow-cyan-500/15 backdrop-blur"
                >
                    <div class="border-b border-white/5 bg-white/5 px-6 py-4">
                        <p class="text-sm font-semibold text-white">Carga y validación</p>
                        <p class="text-xs text-slate-300/80">
                            Selecciona el archivo y ejecuta el proceso de validación.
                        </p>
                    </div>

                    <form @submit.prevent="uploadFile" class="space-y-6 px-6 py-6">
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
            </div>
        </div>
    </AppLayout>
</template>
<script lang="ts">
import { Head as InertiaHead } from '@inertiajs/vue3';
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
            message: null as string | null,
            messageType: null as string | null
        }
    },
    methods: {
        handleFileChange(event: Event) {
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
        getCsrfToken() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                return metaTag.getAttribute('content');
            }
            const name = 'XSRF-TOKEN=';
            const cookies = document.cookie.split(';');
            for (let cookie of cookies) {
                cookie = cookie.trim();
                if (cookie.indexOf(name) === 0) {
                    return decodeURIComponent(cookie.substring(name.length));
                }
            }
            return null;
        },
        async uploadFile() {
            this.message = null;
            if (!this.selectedFile) {
                this.showMessage('Por favor seleccione un archivo', 'error');
                return;
            }
            console.log('Archivo seleccionado:', {
                name: this.selectedFile.name,
                size: this.selectedFile.size,
                type: this.selectedFile.type,
                sizeMB: (this.selectedFile.size / (1024 * 1024)).toFixed(2) + ' MB'
            });

            this.uploading = true;
            this.message = null;
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            console.log('FormData entries:');
            for (const pair of formData.entries()) {
                console.log(pair[0] + ': ', pair[1]);
            }

            const csrfToken = this.getCsrfToken();
            if (!csrfToken) {
                this.showMessage('Error: No se pudo obtener el token de seguridad', 'error');
                this.uploading = false;
                return;
            }

            try {
                const response = await axios.post(admin.excel.upload.url(), formData, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    transformRequest: [(data) => {
                        return data;
                    }],
                    timeout: 300000, // 5 minutos para archivos grandes
                });
                if (response.data.success) {
                    this.showMessage(`Archivo "${response.data['data'].excel_name}" procesado correctamente.`, 'success');
                    this.selectedFile = null;
                    const fileInput = this.$refs.fileInput as HTMLInputElement;
                    if (fileInput) fileInput.value = '';
                } else {
                    this.showMessage(response.data.message || 'Error al procesar el archivo', 'error');
                }
            } catch (error: any) {
                console.error('=== ERROR DETALLADO ===');
                console.error('Error completo:', error);
                console.error('Error response data:', error.response?.data);
                console.error('Error response status:', error.response?.status);
                console.error('Archivo enviado:', {
                    name: this.selectedFile?.name,
                    size: this.selectedFile?.size,
                    type: this.selectedFile?.type
                });
                console.error('========================');

                if (error.response) {
                    if (error.response.status === 422) {
                        const responseData = error.response.data;
                        let errorMessages = [];

                        if (responseData.errors) {
                            if (responseData.errors.file) {
                                errorMessages = Array.isArray(responseData.errors.file)
                                    ? responseData.errors.file
                                    : [responseData.errors.file];
                            } else {
                                Object.values(responseData.errors).forEach(err => {
                                    if (Array.isArray(err)) {
                                        errorMessages.push(...err);
                                    } else {
                                        errorMessages.push(err);
                                    }
                                });
                            }
                        } else if (responseData.message) {
                            if (Array.isArray(responseData.message)) {
                                errorMessages = responseData.message;
                            } else {
                                errorMessages = [responseData.message];
                            }
                        }

                        let finalMessage = '';
                        if (errorMessages.length > 0) {
                            finalMessage = errorMessages.join(', ');
                        } else if (responseData.message === 'The file failed to upload.' || responseData.message === 'El archivo no se recibió correctamente.') {
                            finalMessage = 'El archivo no se pudo subir. Verifique que el archivo no esté corrupto y que su conexión sea estable.';
                        } else {
                            finalMessage = 'Error de validación. Verifique que el archivo sea .xlsx, .xls o .csv y no exceda 20MB.';
                        }

                        this.showMessage(finalMessage, 'error');
                    } else {
                        const errorMessage = error.response.data?.message
                            || (Array.isArray(error.response.data?.message)
                                ? error.response.data.message.join(', ')
                                : 'Error al procesar el archivo');
                        this.showMessage(errorMessage, 'error');
                    }
                } else if (error.request) {
                    this.showMessage('Error de conexión. El servidor no respondió. Verifique su conexión a internet.', 'error');
                } else if (error.code === 'ECONNABORTED') {
                    this.showMessage('La subida del archivo tardó demasiado. Intente con un archivo más pequeño o verifique su conexión.', 'error');
                } else {
                    this.showMessage('Error inesperado: ' + (error.message || 'Error desconocido'), 'error');
                }
            } finally {
                this.uploading = false;
            }
        },
        showMessage(text: string, type: string) {
            this.message = text;
            this.messageType = type;
        },
        formatFileSize(bytes: number) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>