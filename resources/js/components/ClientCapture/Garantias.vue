<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        garantias_ofrecidas?: string;
        descripcion_garantial_i?: string;
        descripcion_garantial_ii?: string;
        descripcion_garantial_iii?: string;
        descripcion_garantial_iv?: string;
        descripcion_garantial_v?: string;
        eleccion?: string;
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
        <!-- Declaración de Bienes -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Declaración de Bienes
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Garantías ofrecidas
                    </label>
                    <textarea
                        :value="modelValue.garantias_ofrecidas"
                        @input="updateField('garantias_ofrecidas', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Descripción garantíal
                    </label>
                    <textarea
                        :value="modelValue.descripcion_garantial_i"
                        @input="updateField('descripcion_garantial_i', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Descripción garantíaII
                    </label>
                    <textarea
                        :value="modelValue.descripcion_garantial_ii"
                        @input="updateField('descripcion_garantial_ii', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Descripción garantíaIII
                    </label>
                    <textarea
                        :value="modelValue.descripcion_garantial_iii"
                        @input="updateField('descripcion_garantial_iii', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Descripción garantíaIV
                    </label>
                    <textarea
                        :value="modelValue.descripcion_garantial_iv"
                        @input="updateField('descripcion_garantial_iv', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Descripción garantíaV
                    </label>
                    <textarea
                        :value="modelValue.descripcion_garantial_v"
                        @input="updateField('descripcion_garantial_v', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Elección
                    </label>
                    <select
                        :value="modelValue.eleccion"
                        @input="updateField('eleccion', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                        <option value="OPCION_1">Opción 1</option>
                        <option value="OPCION_2">Opción 2</option>
                        <option value="OPCION_3">Opción 3</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
