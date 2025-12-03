<template>

    <Head title="Excel" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Subir Archivo Excel</h2>

                <form @submit.prevent="uploadFile" class="space-y-6">
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar archivo Excel
                        </label>
                        <div class="relative">
                            <input id="file" type="file" ref="fileInput" @change="handleFileChange"
                                accept=".xlsx,.xls,.csv"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                :disabled="uploading" />
                            <button type="button" @click="$refs.fileInput.click()" :disabled="uploading" class="w-full bg-blue-50 text-blue-700 py-2 px-4 rounded-full
                                       border border-blue-200 hover:bg-blue-100
                                       disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed
                                       transition duration-200 font-semibold text-sm">
                                Seleccione Excel
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Formatos permitidos: .xlsx, .xls, .csv (Máximo 20MB)
                        </p>
                    </div>

                    <div v-if="selectedFile" class="bg-gray-50 p-4 rounded">
                        <p class="text-sm text-gray-700">
                            <span class="font-medium">Archivo seleccionado:</span> {{ selectedFile.name }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Tamaño: {{ formatFileSize(selectedFile.size) }}
                        </p>
                    </div>

                    <div v-if="message" :class="[
                        'p-4 rounded',
                        messageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'
                    ]">
                        {{ message }}
                    </div>

                    <button type="submit" :disabled="!selectedFile || uploading" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg
                               hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed
                               transition duration-200 flex items-center justify-center">
                        <span v-if="uploading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Procesando...
                        </span>
                        <span v-else style="color: white !important;">Subir y procesar archivo</span>
                    </button>
                </form>
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
                    title: 'Excel',
                    href: admin.excel.index()
                }
            ],
            selectedFile: null,
            uploading: false,
            message: null,
            messageType: null
        }
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 20 * 1024 * 1024) {
                    this.showMessage('El archivo es demasiado grande. Máximo 20MB.', 'error');
                    event.target.value = '';
                    return;
                }
                const validExtensions = ['.xlsx', '.xls', '.csv'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                if (!validExtensions.includes(fileExtension)) {
                    this.showMessage('Formato de archivo no válido. Use .xlsx, .xls o .csv', 'error');
                    event.target.value = '';
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
            for (let pair of formData.entries()) {
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
                    this.$refs.fileInput.value = '';
                } else {
                    this.showMessage(response.data.message || 'Error al procesar el archivo', 'error');
                }
            } catch (error) {
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
        showMessage(text, type) {
            this.message = text;
            this.messageType = type;
        },
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>