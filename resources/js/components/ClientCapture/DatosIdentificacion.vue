<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        primer_nombre?: string;
        segundo_nombre?: string;
        apellido_paterno?: string;
        apellido_materno?: string;
        sexo?: string;
        fecha_nacimiento?: string;
        estado_civil?: string;
        identificacion?: string;
        clave_identificacion?: string;
        entidad_nacimiento?: string;
        rfc_homoclave?: string;
        curp?: string;
        escolaridad?: string;
        // Domicilio del solicitante
        codigo_postal?: string;
        estado?: string;
        municipio?: string;
        ciudad?: string;
        colonia?: string;
        calle?: string;
        numero_exterior?: string;
        entre_calle?: string;
        y_calle?: string;
        pais_domicilio?: string;
        // Datos complementarios
        fecha_posesion?: string;
        tiempo_vivir?: string;
        tipo_propiedad?: string;
        tipo_domicilio?: string;
        tipo_asentamiento?: string;
        // Datos del cónyuge
        nombre_conyuge?: string;
        telefono_laboral_conyuge?: string;
        telefono_celular_conyuge?: string;
        domicilio_laboral_conyuge?: string;
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
        <!-- Información personal del solicitante -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Información personal del solicitante
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Primer nombre <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.primer_nombre"
                        @input="updateField('primer_nombre', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Segundo nombre
                    </label>
                    <input
                        type="text"
                        :value="modelValue.segundo_nombre"
                        @input="updateField('segundo_nombre', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Apellido paterno <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.apellido_paterno"
                        @input="updateField('apellido_paterno', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Apellido materno
                    </label>
                    <input
                        type="text"
                        :value="modelValue.apellido_materno"
                        @input="updateField('apellido_materno', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Sexo <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.sexo"
                        @input="updateField('sexo', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Fecha de nacimiento <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        :value="modelValue.fecha_nacimiento"
                        @input="updateField('fecha_nacimiento', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Estado civil
                    </label>
                    <select
                        :value="modelValue.estado_civil"
                        @input="updateField('estado_civil', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="SOLTERO">Soltero(a)</option>
                        <option value="CASADO">Casado(a)</option>
                        <option value="DIVORCIADO">Divorciado(a)</option>
                        <option value="VIUDO">Viudo(a)</option>
                        <option value="UNION_LIBRE">Unión libre</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Identificación <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.identificacion"
                        @input="updateField('identificacion', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="INE">INE</option>
                        <option value="PASAPORTE">Pasaporte</option>
                        <option value="CEDULA">Cédula profesional</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Clave de identificación
                    </label>
                    <input
                        type="text"
                        :value="modelValue.clave_identificacion"
                        @input="updateField('clave_identificacion', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Entidad de nacimiento <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.entidad_nacimiento"
                        @input="updateField('entidad_nacimiento', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="AGUASCALIENTES">Aguascalientes</option>
                        <option value="BAJA_CALIFORNIA">Baja California</option>
                        <option value="CDMX">Ciudad de México</option>
                        <!-- Agregar más estados -->
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        RFC con homoclave <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2 items-center">
                        <input
                            type="text"
                            :value="modelValue.rfc_homoclave"
                            @input="updateField('rfc_homoclave', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                        />
                        <button
                            type="button"
                            class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 whitespace-nowrap"
                        >
                            Clic para generar RFC
                        </button>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        CURP <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2 items-center">
                        <input
                            type="text"
                            :value="modelValue.curp"
                            @input="updateField('curp', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                        />
                        <button
                            type="button"
                            class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 whitespace-nowrap"
                        >
                            Clic para generar CURP
                        </button>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Escolaridad
                    </label>
                    <select
                        :value="modelValue.escolaridad"
                        @input="updateField('escolaridad', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="PRIMARIA">Primaria</option>
                        <option value="SECUNDARIA">Secundaria</option>
                        <option value="PREPARATORIA">Preparatoria</option>
                        <option value="LICENCIATURA">Licenciatura</option>
                        <option value="POSGRADO">Posgrado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Domicilio del solicitante -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Domicilio del solicitante
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Código postal <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.codigo_postal"
                        @input="updateField('codigo_postal', ($event.target as HTMLInputElement).value)"
                        placeholder="Introducir código postal"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.estado"
                        @input="updateField('estado', ($event.target as HTMLInputElement).value)"
                        placeholder="Introducir código postal"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Municipio/Delegación/Alcaldía <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.municipio"
                        @input="updateField('municipio', ($event.target as HTMLInputElement).value)"
                        placeholder="Introducir código postal"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Ciudad
                    </label>
                    <input
                        type="text"
                        :value="modelValue.ciudad"
                        @input="updateField('ciudad', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Colonia <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.colonia"
                        @input="updateField('colonia', ($event.target as HTMLInputElement).value)"
                        placeholder="Introducir código postal"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Calle <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.calle"
                        @input="updateField('calle', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Número exterior <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.numero_exterior"
                        @input="updateField('numero_exterior', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Entre la calle de <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.entre_calle"
                        @input="updateField('entre_calle', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Y la calle de <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.y_calle"
                        @input="updateField('y_calle', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        País domicilio <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.pais_domicilio"
                        @input="updateField('pais_domicilio', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="MEXICO">México</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Datos complementarios del domicilio -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos complementarios del domicilio
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Fecha de toma de posesión
                    </label>
                    <input
                        type="date"
                        :value="modelValue.fecha_posesion"
                        @input="updateField('fecha_posesion', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Tiempo de vivir en el domicilio <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        :value="modelValue.tiempo_vivir"
                        @input="updateField('tiempo_vivir', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Tipo de propiedad <span class="text-red-500">*</span>
                    </label>
                    <select
                        :value="modelValue.tipo_propiedad"
                        @input="updateField('tipo_propiedad', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="PROPIA">Propia</option>
                        <option value="RENTADA">Rentada</option>
                        <option value="FAMILIAR">Familiar</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Tipo de domicilio
                    </label>
                    <select
                        :value="modelValue.tipo_domicilio"
                        @input="updateField('tipo_domicilio', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="CASA">Casa</option>
                        <option value="DEPARTAMENTO">Departamento</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Tipo de asentamiento
                    </label>
                    <select
                        :value="modelValue.tipo_asentamiento"
                        @input="updateField('tipo_asentamiento', ($event.target as HTMLSelectElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    >
                        <option value="">---------</option>
                        <option value="URBANO">Urbano</option>
                        <option value="RURAL">Rural</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Datos del cónyuge -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos del cónyuge
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Nombre del cónyuge
                    </label>
                    <input
                        type="text"
                        :value="modelValue.nombre_conyuge"
                        @input="updateField('nombre_conyuge', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Teléfono laboral del cónyuge
                    </label>
                    <input
                        type="tel"
                        :value="modelValue.telefono_laboral_conyuge"
                        @input="updateField('telefono_laboral_conyuge', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Teléfono celular del cónyuge
                    </label>
                    <input
                        type="tel"
                        :value="modelValue.telefono_celular_conyuge"
                        @input="updateField('telefono_celular_conyuge', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700 dark:text-slate-300">
                        Domicilio laboral del cónyuge
                    </label>
                    <input
                        type="text"
                        :value="modelValue.domicilio_laboral_conyuge"
                        @input="updateField('domicilio_laboral_conyuge', ($event.target as HTMLInputElement).value)"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-900"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
