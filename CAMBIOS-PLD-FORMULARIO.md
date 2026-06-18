# Cambios en Formulario PLD - Captura de Cliente

**Fecha:** 2026-02-23  
**Descripción:** Agregados nuevos campos en la sección P.L.D. (Prevención de Lavado de Dinero)

## Campos Agregados

### 1. Datos Económicos del Cliente

Nueva sección con los siguientes campos:

| Campo | Tipo | Requerido | Opciones |
|-------|------|-----------|----------|
| ¿Maneja efectivo en moneda nacional? | Select | Sí | Sí / No |
| ¿Maneja efectivo en moneda extranjera? | Select | Sí | Sí / No |
| ¿Forma en que dispondrá su crédito? | Select | Sí | Transferencia / Cheque / Efectivo / Depósito |
| Tipo de póliza | Input Text | No | Texto libre |
| Observaciones | Textarea | No | Texto libre |

### 2. Perfil Transaccional

Nueva sección con los siguientes campos:

| Campo | Tipo | Requerido | Opciones |
|-------|------|-----------|----------|
| ¿Se presentarán pagos extra? | Select | Sí | Sí / No |
| ¿Forma en que paga el crédito? | Select | No | Transferencia / Domiciliación / Efectivo / Cheque / Depósito |
| ¿Segunda forma en que pagará el crédito? | Select | No | Transferencia / Domiciliación / Efectivo / Cheque / Depósito |
| Canal de pago | Select | No | Sucursal / Internet / App móvil / Domiciliación |
| Monto de pago máximo | Number | Sí | Valor numérico decimal |

## Archivos Modificados

### 1. `/resources/js/components/ClientCapture/PLD.vue`

**Cambios en el script:**
- Agregados 10 nuevos campos al tipo `modelValue`:
  - `maneja_efectivo_nacional`
  - `maneja_efectivo_extranjero`
  - `forma_dispone_credito`
  - `observaciones_economicas`
  - `tipo_poliza`
  - `presentara_pagos_extra`
  - `forma_paga_credito`
  - `segunda_forma_pago`
  - `canal_pago`
  - `monto_pago_maximo`

**Cambios en el template:**
- Agregada sección "Datos económicos del cliente" con estilos en verde (`text-emerald-700`)
- Agregada sección "Perfil transaccional" con estilos en azul (`text-blue-700`)
- Todos los campos conectados con `v-model` mediante `updateField`

## Almacenamiento en Base de Datos

### Estructura JSON en columna `pld`

Los datos se guardan en la columna `pld` (tipo JSON) de la tabla `client_captures`:

```json
{
  "calificacion_riesgo": "BAJO",
  "motivo_riesgo": "...",
  "recursos_operacion": "...",
  "nacionalidad": "MEXICANA",
  "pais_origen": "MEXICO",
  "ocupacion": "EMPLEADO",
  "profesion": "OTRA",
  "giro_negocio": "...",
  "origen_recursos": "EMPLEO",
  "num_fiel": "...",
  "observaciones_pld": "...",
  "pais_domicilio_extranjero": "...",
  "codigo_postal_extranjero": "...",
  "maneja_efectivo_nacional": "SI",
  "maneja_efectivo_extranjero": "NO",
  "forma_dispone_credito": "TRANSFERENCIA",
  "observaciones_economicas": "...",
  "tipo_poliza": "...",
  "presentara_pagos_extra": "NO",
  "forma_paga_credito": "TRANSFERENCIA",
  "segunda_forma_pago": "DEPOSITO",
  "canal_pago": "INTERNET",
  "monto_pago_maximo": "50000.00"
}
```

### Migración Existente

✅ La columna `pld` ya existe en la base de datos (JSON type)  
✅ Creada en: `2026_02_16_194952_add_payload_columns_to_client_captures_table.php`  
✅ No se requiere nueva migración

## Validaciones

### Campos Requeridos (marcados con *)
- Maneja efectivo en moneda nacional
- Maneja efectivo en moneda extranjera
- Forma en que dispondrá su crédito
- Se presentarán pagos extra
- Monto de pago máximo

### Campos Opcionales
- Tipo de póliza
- Observaciones económicas
- Forma en que paga el crédito
- Segunda forma en que pagará el crédito
- Canal de pago

## Funcionalidad

### Guardado Automático
- Los datos se guardan automáticamente al hacer clic en "Guardar información"
- Se integran con el resto del formulario de captura de cliente
- Se almacenan como JSON en la columna `pld`

### Recuperación de Datos
- Al editar un cliente existente, los datos se cargan desde el JSON
- Todos los campos mantienen su estado
- Compatible con el sistema de validación existente

## Estilos y UI

### Diseño Responsive
- Grid de 2 columnas en pantallas medianas (`md:grid-cols-2`)
- Espaciado consistente (`space-y-4`, `gap-4`)
- Compatible con modo oscuro (`dark:`)

### Código de Colores por Sección
- **Prevención de lavado de dinero:** Gris (`text-slate-700`)
- **Datos económicos del cliente:** Verde (`text-emerald-700`)
- **Perfil transaccional:** Azul (`text-blue-700`)

## Verificación

✅ Campos agregados al formulario  
✅ TypeScript types actualizados  
✅ Binding bidireccional configurado  
✅ Base de datos ya preparada (columna JSON)  
✅ Validaciones implementadas  
✅ Estilos responsive aplicados  
✅ Compatible con modo oscuro

## Notas Adicionales

- Todos los campos nuevos son opcionales en la base de datos (pueden ser `null`)
- El campo `monto_pago_maximo` acepta valores decimales (step="0.01")
- Las opciones de los selectores pueden ser personalizadas según las necesidades
- El sistema guarda automáticamente todos los cambios en tiempo real
