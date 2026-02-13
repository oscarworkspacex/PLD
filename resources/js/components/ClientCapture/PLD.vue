<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        calificacion_riesgo?: string;
        motivo_riesgo?: string;
        recursos_operacion?: string;
        nacionalidad?: string;
        pais_origen?: string;
        ocupacion?: string;
        profesion?: string;
        giro_negocio?: string;
        origen_recursos?: string;
        num_fiel?: string;
        observaciones_pld?: string;
        pais_domicilio_extranjero?: string;
        codigo_postal_extranjero?: string;
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
        <!-- Prevención de lavado de dinero -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Prevención de lavado de dinero
            </h3>
            
            <!-- Clasificación de riesgo -->
            <div class="mb-6">
                <h4 class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-300">Clasificación de riesgo</h4>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Calificación de riesgo asignada por el promotor <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.calificacion_riesgo"
                            @input="updateField('calificacion_riesgo', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">BAJO</option>
                            <option value="MEDIO">MEDIO</option>
                            <option value="ALTO">ALTO</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Por qué se otorga nivel de riesgo?
                        </label>
                        <textarea
                            :value="modelValue.motivo_riesgo"
                            @input="updateField('motivo_riesgo', ($event.target as HTMLTextAreaElement).value)"
                            rows="3"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        ></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Los recursos del crédito se destinarán para operar? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.recursos_operacion"
                            @input="updateField('recursos_operacion', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Opera por cuenta propia</option>
                            <option value="TERCEROS">Opera por cuenta de terceros</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Datos personales del cliente -->
            <div>
                <h4 class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-300">Datos personales del cliente</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Nacionalidad <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.nacionalidad"
                            @input="updateField('nacionalidad', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">MEXICANA</option>
                            <option value="ESTADOUNIDENSE">Estadounidense</option>
                            <option value="OTRA">Otra</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            País de origen <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.pais_origen"
                            @input="updateField('pais_origen', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">MEXICO</option>
                            <option value="USA">Estados Unidos</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Ocupación <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.ocupacion"
                            @input="updateField('ocupacion', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">EMPLEADO</option>
                            <option value="PROFESIONISTA">Profesionista</option>
                            <option value="EMPRESARIO">Empresario</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Profesión <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.profesion"
                            @input="updateField('profesion', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">OTRA</option>
                            <option value="INGENIERO">Ingeniero</option>
                            <option value="ABOGADO">Abogado</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Giro del negocio <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.giro_negocio"
                            @input="updateField('giro_negocio', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">EMPLEADO DEL SECTOR PRIVADO</option>
                            <option value="COMERCIO">Comercio</option>
                            <option value="SERVICIOS">Servicios</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Origen de los recursos <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.origen_recursos"
                            @input="updateField('origen_recursos', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">EMPLEO</option>
                            <option value="NEGOCIO">Negocio propio</option>
                            <option value="HERENCIA">Herencia</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Número de F.I.E.L.
                        </label>
                        <input
                            type="text"
                            :value="modelValue.num_fiel"
                            @input="updateField('num_fiel', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Observaciones
                        </label>
                        <textarea
                            :value="modelValue.observaciones_pld"
                            @input="updateField('observaciones_pld', ($event.target as HTMLTextAreaElement).value)"
                            rows="2"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Domicilio en el extranjero cliente -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-300">Domicilio en el extranjero cliente</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            País domicilio
                        </label>
                        <select
                            :value="modelValue.pais_domicilio_extranjero"
                            @input="updateField('pais_domicilio_extranjero', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">---------</option>
                            <option value="USA">Estados Unidos</option>
                            <option value="CANADA">Canadá</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Código postal
                        </label>
                        <input
                            type="text"
                            :value="modelValue.codigo_postal_extranjero"
                            @input="updateField('codigo_postal_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
