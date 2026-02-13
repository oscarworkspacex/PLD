<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        telefono_casa?: string;
        telefono_recados?: string;
        telefono_celular?: string;
        email?: string;
        skype?: string;
        facebook?: string;
        compartir_info?: string;
    };
}>();

const emit = defineEmits<{
    'update:modelValue': [value: typeof props.modelValue];
}>();

const updateField = (field: string, value: string) => {
    emit('update:modelValue', {
        ...props.modelValue,
        [field]: value,
    });
};
</script>

<template>
    <div class="space-y-6">
        <!-- Datos de contacto del solicitante -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos de contacto del solicitante
            </h3>
            
            <!-- Teléfonos -->
            <div class="mb-4">
                <h4 class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Teléfonos</h4>
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Teléfono de casa <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">📞</span>
                            <input
                                type="tel"
                                :value="modelValue.telefono_casa"
                                @input="updateField('telefono_casa', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-10 text-sm dark:border-slate-700 dark:bg-slate-900"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Teléfono de recados
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">📞</span>
                            <input
                                type="tel"
                                :value="modelValue.telefono_recados"
                                @input="updateField('telefono_recados', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-10 text-sm dark:border-slate-700 dark:bg-slate-900"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Teléfono celular <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">📱</span>
                            <input
                                type="tel"
                                :value="modelValue.telefono_celular"
                                @input="updateField('telefono_celular', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-10 text-sm dark:border-slate-700 dark:bg-slate-900"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Otros -->
            <div>
                <h4 class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300">Otros</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">✉️</span>
                            <input
                                type="email"
                                :value="modelValue.email"
                                @input="updateField('email', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-10 text-sm dark:border-slate-700 dark:bg-slate-900"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Skype
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">📞</span>
                            <input
                                type="text"
                                :value="modelValue.skype"
                                @input="updateField('skype', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-10 text-sm dark:border-slate-700 dark:bg-slate-900"
                            />
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            Facebook
                        </label>
                        <input
                            type="text"
                            :value="modelValue.facebook"
                            @input="updateField('facebook', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                            ¿Compartir información con fines mercadológicos?
                        </label>
                        <select
                            :value="modelValue.compartir_info"
                            @input="updateField('compartir_info', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                        >
                            <option value="">---------</option>
                            <option value="SI">Sí</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
