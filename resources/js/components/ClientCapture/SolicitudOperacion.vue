<script setup lang="ts">
const props = defineProps<{
    modelValue: {
        // Capacidad de pago - Ingresos
        ingresos_brutos?: string;
        ingresos_netos?: string;
        otros_ingresos?: string;
        // Egresos
        gastos_fijos?: string;
        deudas_actuales?: string;
        // Operación solicitada
        producto_financiero?: string;
        forma_pago?: string;
        tasa_anual?: string;
        comision?: string;
        plazo_solicitado?: string;
        monto_solicitado?: string;
        fecha_disposicion?: string;
        fecha_primera_cuota?: string;
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
        <!-- Capacidad de pago -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Capacidad de pago
            </h3>
            
            <!-- Ingresos -->
            <div class="mb-4">
                <h4 class="mb-2 text-sm font-semibold text-green-700 dark:text-green-400">Ingresos</h4>
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Ingresos brutos (mens.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.ingresos_brutos"
                                @input="updateField('ingresos_brutos', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Ingresos netos (mens.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.ingresos_netos"
                                @input="updateField('ingresos_netos', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Otros ingresos (mens.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.otros_ingresos"
                                @input="updateField('otros_ingresos', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Egresos -->
            <div>
                <h4 class="mb-2 text-sm font-semibold text-red-700 dark:text-red-400">Egresos</h4>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Gastos fijos (mens.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.gastos_fijos"
                                @input="updateField('gastos_fijos', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Deudas actuales (mens.)
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.deudas_actuales"
                                @input="updateField('deudas_actuales', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-sm text-slate-600 dark:text-slate-400 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-md">
                <p><strong>Datos asociados a la operación</strong></p>
                <p class="text-xs mt-1">Los siguientes campos se calcularán automáticamente según los ingresos y egresos capturados.</p>
            </div>
        </div>

        <!-- Operación solicitada -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Operación solicitada
            </h3>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">1</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Producto financiero <span class="text-red-500">*</span>
                        </label>
                        <select
                            :value="modelValue.producto_financiero"
                            @input="updateField('producto_financiero', ($event.target as HTMLSelectElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        >
                            <option value="">---------</option>
                            <option value="PRESTAMO_PERSONAL">Préstamo personal</option>
                            <option value="PRESTAMO_AUTO">Préstamo auto</option>
                            <option value="PRESTAMO_NOMINA">Préstamo nómina</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">2</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Forma de pago <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left text-sm dark:border-slate-700 dark:bg-slate-900"
                        >
                            Seleccionar producto
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">3</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Tasa anual <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left text-sm dark:border-slate-700 dark:bg-slate-900"
                        >
                            Seleccionar producto
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">4</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Comisión % <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left text-sm dark:border-slate-700 dark:bg-slate-900"
                        >
                            Seleccionar producto
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">5</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Plazo solicitado <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-left text-sm text-slate-400 dark:border-slate-700 dark:bg-slate-900"
                        >
                            ... Seleccionar producto
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">6</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Monto solicitado <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">$</span>
                            <input
                                type="text"
                                :value="modelValue.monto_solicitado"
                                @input="updateField('monto_solicitado', ($event.target as HTMLInputElement).value)"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pl-7 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">7</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Fecha de disposición <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            :value="modelValue.fecha_disposicion"
                            @input="updateField('fecha_disposicion', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">8</span>
                    <div class="flex-1">
                        <label class="mb-1 block text-sm font-medium text-slate-900 dark:text-slate-300">
                            Fecha de inicio o primera cuota <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            :value="modelValue.fecha_primera_cuota"
                            @input="updateField('fecha_primera_cuota', ($event.target as HTMLInputElement).value)"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos de depósito bancario -->
        <div>
            <h3 class="mb-3 text-base font-semibold text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 px-3 py-2 rounded">
                Datos de depósito bancario
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                (Información bancaria para depósito del préstamo)
            </p>
        </div>
    </div>
</template>
