<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        situacion_laboral?: string;
        tipo_cliente?: string;
        nombre_empresa?: string;
        centro_trabajo?: string;
        empresa_ii?: string;
        tipo_contrato?: string;
        puesto?: string;
        departamento?: string;
        jefe_inmediato?: string;
        puesto_jefe?: string;
        actividad_economica?: string;
        num_dependientes?: string;
        fecha_ingreso?: string;
        num_empleado?: string;
        num_seguro_social?: string;
        // Domicilio de la empresa (continuará en el siguiente mensaje)
        telefono_laboral?: string;
        extension?: string;
        email_trabajo?: string;
        hora_entrada?: string;
        hora_salida?: string;
        observaciones?: string;
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
        <!-- Datos asociados al empleo -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos asociados al empleo
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Situación laboral
                    </label>
                    <select
                        :value="modelValue.situacion_laboral"
                        @input="updateField('situacion_laboral', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                        <option value="EMPLEADO">Empleado</option>
                        <option value="INDEPENDIENTE">Independiente</option>
                        <option value="DESEMPLEADO">Desempleado</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Tipo de cliente
                    </label>
                    <select
                        :value="modelValue.tipo_cliente"
                        @input="updateField('tipo_cliente', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                        <option value="NUEVO">Nuevo</option>
                        <option value="RECURRENTE">Recurrente</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Nombre de la empresa
                    </label>
                    <input
                        type="text"
                        :value="modelValue.nombre_empresa"
                        @input="updateField('nombre_empresa', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Centro de trabajo
                    </label>
                    <select
                        :value="modelValue.centro_trabajo"
                        @input="updateField('centro_trabajo', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Empresa II
                    </label>
                    <input
                        type="text"
                        :value="modelValue.empresa_ii"
                        @input="updateField('empresa_ii', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Tipo de contrato
                    </label>
                    <select
                        :value="modelValue.tipo_contrato"
                        @input="updateField('tipo_contrato', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">---------</option>
                        <option value="PLANTA">Planta</option>
                        <option value="TEMPORAL">Temporal</option>
                        <option value="HONORARIOS">Honorarios</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Puesto
                    </label>
                    <input
                        type="text"
                        :value="modelValue.puesto"
                        @input="updateField('puesto', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Departamento
                    </label>
                    <input
                        type="text"
                        :value="modelValue.departamento"
                        @input="updateField('departamento', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Jefe inmediato
                    </label>
                    <input
                        type="text"
                        :value="modelValue.jefe_inmediato"
                        @input="updateField('jefe_inmediato', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Puesto jefe
                    </label>
                    <input
                        type="text"
                        :value="modelValue.puesto_jefe"
                        @input="updateField('puesto_jefe', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Actividad económica <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.actividad_economica"
                        @input="updateField('actividad_economica', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    >
                        <option value="">EMPLEADO DEL SECTOR PRIVADO</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Número de dependientes económicos
                    </label>
                    <input
                        type="number"
                        :value="modelValue.num_dependientes"
                        @input="updateField('num_dependientes', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
            </div>
        </div>

        <!-- Datos de identificación -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos de identificación
            </h3>
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Fecha de ingreso
                    </label>
                    <input
                        type="date"
                        :value="modelValue.fecha_ingreso"
                        @input="updateField('fecha_ingreso', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Número de empleado
                    </label>
                    <input
                        type="text"
                        :value="modelValue.num_empleado"
                        @input="updateField('num_empleado', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Número de Seguridad Social (NSS)
                    </label>
                    <input
                        type="text"
                        :value="modelValue.num_seguro_social"
                        @input="updateField('num_seguro_social', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
            </div>
        </div>

        <!-- Domicilio de la empresa -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Domicilio de la empresa
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                (Este apartado se llenará automáticamente al seleccionar el centro de trabajo)
            </p>
        </div>

        <!-- Datos de contacto laborales -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos de contacto laborales
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Teléfono laboral
                    </label>
                    <input
                        type="tel"
                        :value="modelValue.telefono_laboral"
                        @input="updateField('telefono_laboral', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Extensión
                    </label>
                    <input
                        type="text"
                        :value="modelValue.extension"
                        @input="updateField('extension', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Email de trabajo
                    </label>
                    <input
                        type="email"
                        :value="modelValue.email_trabajo"
                        @input="updateField('email_trabajo', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Hora de entrada
                    </label>
                    <input
                        type="time"
                        :value="modelValue.hora_entrada"
                        @input="updateField('hora_entrada', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Hora de salida
                    </label>
                    <input
                        type="time"
                        :value="modelValue.hora_salida"
                        @input="updateField('hora_salida', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    />
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                        Observaciones
                    </label>
                    <textarea
                        :value="modelValue.observaciones"
                        @input="updateField('observaciones', ($event.target as HTMLTextAreaElement).value)"
                        rows="3"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                    ></textarea>
                </div>
            </div>
        </div>
    </div>
</template>
