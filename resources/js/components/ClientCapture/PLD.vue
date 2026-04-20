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
        estado_domicilio_extranjero?: string;
        municipio_domicilio_extranjero?: string;
        ciudad_domicilio_extranjero?: string;
        calle_domicilio_extranjero?: string;
        numero_interior_domicilio_extranjero?: string;
        numero_exterior_domicilio_extranjero?: string;
        referencias_domicilio_extranjero?: string;
        cargo_publico_titular?: string;
        tiempo_cargo_publico_titular?: string;
        tiempo_desde_dejo_cargo_titular?: string;
        familiar_cargo_publico?: string;
        nombre_familiar_cargo_publico?: string;
        tiempo_cargo_publico_familiar?: string;
        tiempo_desde_dejo_cargo_familiar?: string;
        utiliza_tarjeta_credito?: string;
        institucion_bancaria?: string;
        ultimos_4_digitos_tarjeta?: string;
        tiene_poliza_seguro?: string;
        poliza_bancaria?: string;
        // Nuevos campos - Datos económicos
        maneja_efectivo_nacional?: string;
        maneja_efectivo_extranjero?: string;
        forma_dispone_credito?: string;
        observaciones_economicas?: string;
        tipo_poliza?: string;
        // Nuevos campos - Perfil transaccional
        presentara_pagos_extra?: string;
        forma_paga_credito?: string;
        segunda_forma_pago?: string;
        canal_pago?: string;
        monto_pago_maximo?: string;
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
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Estado
                        </label>
                        <input
                            type="text"
                            :value="modelValue.estado_domicilio_extranjero"
                            @input="updateField('estado_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Municipio/Delegación/Alcaldía
                        </label>
                        <input
                            type="text"
                            :value="modelValue.municipio_domicilio_extranjero"
                            @input="updateField('municipio_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Ciudad
                        </label>
                        <input
                            type="text"
                            :value="modelValue.ciudad_domicilio_extranjero"
                            @input="updateField('ciudad_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Calle
                        </label>
                        <input
                            type="text"
                            :value="modelValue.calle_domicilio_extranjero"
                            @input="updateField('calle_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Número interior
                        </label>
                        <input
                            type="text"
                            :value="modelValue.numero_interior_domicilio_extranjero"
                            @input="updateField('numero_interior_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Número exterior
                        </label>
                        <input
                            type="text"
                            :value="modelValue.numero_exterior_domicilio_extranjero"
                            @input="updateField('numero_exterior_domicilio_extranjero', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Referencias domicilio
                        </label>
                        <textarea
                            :value="modelValue.referencias_domicilio_extranjero"
                            @input="updateField('referencias_domicilio_extranjero', ($event.target as HTMLTextAreaElement).value)"
                            rows="2"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Persona políticamente expuesta (Titular) -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-emerald-700 dark:text-emerald-300">Persona políticamente expuesta (Titular)</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Cargo público <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.cargo_publico_titular"
                            @input="updateField('cargo_publico_titular', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">SIN PUESTO POLITICO</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Tiempo de ocupar el cargo público
                        </label>
                        <input
                            type="text"
                            :value="modelValue.tiempo_cargo_publico_titular"
                            @input="updateField('tiempo_cargo_publico_titular', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            En su caso, tiempo transcurrido de haber dejado cargo público
                        </label>
                        <input
                            type="text"
                            :value="modelValue.tiempo_desde_dejo_cargo_titular"
                            @input="updateField('tiempo_desde_dejo_cargo_titular', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
            </div>

            <!-- Persona políticamente expuesta (Familiar) -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-emerald-700 dark:text-emerald-300">Persona políticamente expuesta (Familiar)</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Familiar con cargo público? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.familiar_cargo_publico"
                            @input="updateField('familiar_cargo_publico', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">SIN FAMILIARES</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Nombre del familiar con cargo público
                        </label>
                        <input
                            type="text"
                            :value="modelValue.nombre_familiar_cargo_publico"
                            @input="updateField('nombre_familiar_cargo_publico', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Tiempo de ocupar el cargo público
                        </label>
                        <input
                            type="text"
                            :value="modelValue.tiempo_cargo_publico_familiar"
                            @input="updateField('tiempo_cargo_publico_familiar', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            En su caso, tiempo transcurrido de haber dejado cargo público
                        </label>
                        <input
                            type="text"
                            :value="modelValue.tiempo_desde_dejo_cargo_familiar"
                            @input="updateField('tiempo_desde_dejo_cargo_familiar', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
            </div>

            <!-- Datos bancarios del cliente -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-emerald-700 dark:text-emerald-300">Datos bancarios del cliente</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Utiliza tarjeta de crédito? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.utiliza_tarjeta_credito"
                            @input="updateField('utiliza_tarjeta_credito', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">NO</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Institución bancaria
                        </label>
                        <input
                            type="text"
                            :value="modelValue.institucion_bancaria"
                            @input="updateField('institucion_bancaria', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Últimos 4 dígitos
                        </label>
                        <input
                            type="text"
                            maxlength="4"
                            :value="modelValue.ultimos_4_digitos_tarjeta"
                            @input="updateField('ultimos_4_digitos_tarjeta', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Tiene póliza de seguro? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.tiene_poliza_seguro"
                            @input="updateField('tiene_poliza_seguro', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">NO</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Póliza bancaria
                        </label>
                        <input
                            type="text"
                            :value="modelValue.poliza_bancaria"
                            @input="updateField('poliza_bancaria', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Tipo de póliza
                        </label>
                        <input
                            type="text"
                            :value="modelValue.tipo_poliza"
                            @input="updateField('tipo_poliza', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
            </div>

            <!-- Datos económicos del cliente -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-emerald-700 dark:text-emerald-300">Datos económicos del cliente</h4>
                <div class="space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                                ¿Maneja efectivo en moneda nacional? <span class="text-red-500">*</span>
                            </label>
                            <select
                                :value="modelValue.maneja_efectivo_nacional"
                                @input="updateField('maneja_efectivo_nacional', ($event.target as HTMLSelectElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="SI">Sí</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                                ¿Maneja efectivo en moneda extranjera? <span class="text-red-500">*</span>
                            </label>
                            <select
                                :value="modelValue.maneja_efectivo_extranjero"
                                @input="updateField('maneja_efectivo_extranjero', ($event.target as HTMLSelectElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="SI">Sí</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Forma en que dispondrá su crédito? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.forma_dispone_credito"
                            @input="updateField('forma_dispone_credito', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="TRANSFERENCIA">Transferencia electrónica</option>
                            <option value="CHEQUE">Cheque</option>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="DEPOSITO">Depósito</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Observaciones
                        </label>
                        <textarea
                            :value="modelValue.observaciones_economicas"
                            @input="updateField('observaciones_economicas', ($event.target as HTMLTextAreaElement).value)"
                            rows="3"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            placeholder="Observaciones sobre datos económicos..."
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Perfil transaccional -->
            <div class="mt-6">
                <h4 class="mb-3 text-sm font-semibold text-blue-700 dark:text-blue-300">Perfil transaccional</h4>
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Se presentarán pagos extra? <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.presentara_pagos_extra"
                            @input="updateField('presentara_pagos_extra', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="SI">Sí</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Forma en que paga el crédito?
                        </label>
                        <select
                            :value="modelValue.forma_paga_credito"
                            @input="updateField('forma_paga_credito', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="TRANSFERENCIA">Transferencia electrónica</option>
                            <option value="DOMICILIACION">Domiciliación</option>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="CHEQUE">Cheque</option>
                            <option value="DEPOSITO">Depósito</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            ¿Segunda forma en que pagará el crédito?
                        </label>
                        <select
                            :value="modelValue.segunda_forma_pago"
                            @input="updateField('segunda_forma_pago', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">Seleccionar...</option>
                            <option value="TRANSFERENCIA">Transferencia electrónica</option>
                            <option value="DOMICILIACION">Domiciliación</option>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="CHEQUE">Cheque</option>
                            <option value="DEPOSITO">Depósito</option>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                                Canal de pago
                            </label>
                            <select
                                :value="modelValue.canal_pago"
                                @input="updateField('canal_pago', ($event.target as HTMLSelectElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="SUCURSAL">Sucursal bancaria</option>
                                <option value="INTERNET">Internet</option>
                                <option value="APLICACION">Aplicación móvil</option>
                                <option value="DOMICILIACION">Domiciliación</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                                Monto de pago máximo <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                :value="modelValue.monto_pago_maximo"
                                @input="updateField('monto_pago_maximo', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                                placeholder="0.00"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
