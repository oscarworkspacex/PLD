<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        medio_contacto?: string;
        promotor?: string;
        puesto_contacto?: string;
        telefono_contacto?: string;
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
    <div class="space-y-4">
        <div class="rounded-md bg-slate-100 px-3 py-2 text-sm text-slate-700 dark:bg-slate-800 dark:text-slate-300">
            Para uso exclusivo de la SOFOM
        </div>

        <div class="grid gap-4 lg:grid-cols-[1fr_250px]">
            <div class="space-y-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Medio de contacto <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.medio_contacto"
                        @input="updateField('medio_contacto', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                        <option value="PROMOTOR">PROMOTOR</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Promotor <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.promotor"
                        @input="updateField('promotor', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Puesto del contacto
                    </label>
                    <input
                        type="text"
                        :value="modelValue.puesto_contacto"
                        @input="updateField('puesto_contacto', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Teléfono del contacto
                    </label>
                    <input
                        type="text"
                        :value="modelValue.telefono_contacto"
                        @input="updateField('telefono_contacto', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
            </div>

            <div class="h-fit rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-300">
                <p class="mb-2 font-semibold">⚠️ Aviso campos requeridos</p>
                <p class="text-xs">Los campos marcados con * son obligatorios</p>
            </div>
        </div>
    </div>
</template>
