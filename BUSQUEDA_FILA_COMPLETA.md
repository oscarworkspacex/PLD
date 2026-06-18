# Búsqueda por Fila Completa - Documentación

## Fecha de Implementación
10 de Febrero, 2026

## Última Actualización
10 de Febrero, 2026 - Agregada búsqueda con múltiples palabras

## Problema Original

**ANTES**: El sistema buscaba en todas las columnas pero solo devolvía la columna donde encontró el match.

Ejemplo:
- Fila: `Jesus | Ramirez | Fernandez`
- Búsqueda: "Jesus"
- Resultado: Solo "Jesus" ❌

**AHORA**: El sistema busca en todas las columnas y devuelve **TODA LA FILA COMPLETA**.

Ejemplo:
- Fila: `Jesus | Ramirez | Fernandez`
- Búsqueda: "Jesus"
- Resultado: `["Jesus", "Ramirez", "Fernandez"]` ✅

## Cambios Implementados

### 1. Migración de Base de Datos

**Archivo**: `database/migrations/2026_02_10_180751_add_row_data_to_excel_data_table.php`

```php
Schema::table('excel_data', function (Blueprint $table) {
    // Columna JSON para almacenar toda la fila
    $table->json('row_data')->nullable()->after('value');
    // Índice para búsquedas más rápidas
    $table->index('excel_name');
});
```

**Comando ejecutado**:
```bash
docker-compose exec php php artisan migrate
```

### 2. Modelo Actualizado

**Archivo**: `app/Models/ExcelData.php`

```php
protected $fillable = [
    'value',
    'excel_name',
    'row_data',  // ← Nuevo campo
];

protected $casts = [
    'row_data' => 'array',  // ← Cast automático a array
];
```

### 3. Import Modificado

**Archivo**: `app/Imports/ExcelDataImport.php`

**Cambio principal**: En lugar de guardar cada celda individualmente, ahora guarda **toda la fila completa**.

```php
foreach ($rows as $rowIndex => $row) {
    // Filtrar valores vacíos
    $rowData = array_filter($row, function($value) {
        return $value !== null && $value !== '';
    });
    
    if (!empty($rowData)) {
        // Normalizar valores
        $normalizedRow = array_map(function($value) {
            return is_numeric($value) ? (string)$value : $value;
        }, $rowData);
        
        // Crear string concatenado para búsqueda
        $searchableValue = implode(' | ', $normalizedRow);
        
        $dataToInsert[] = [
            'value' => $searchableValue,           // Para búsqueda
            'row_data' => json_encode($normalizedRow), // Fila completa
            'excel_name' => $this->excelName,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
```

### 4. Búsqueda Mejorada

**Archivo**: `app/Http/Controllers/Admin/ExcelController.php`

```php
public function searchData(Request $request)
{
    $value = $request['value'];
    
    // Buscar en el campo 'value' (contiene toda la fila concatenada)
    $data = ExcelData::where('value', 'LIKE', '%' . $value . '%')
        ->orderBy('value', 'asc')
        ->select('id', 'value', 'row_data', 'excel_name', 'created_at')
        ->paginate($pageSize, ['*'], 'page', $current);
    
    // Transformar para incluir row_data
    $data->getCollection()->transform(function ($item) {
        return [
            'id' => $item->id,
            'value' => $item->value,
            'row_data' => $item->row_data,  // ← Array con todas las columnas
            'excel_name' => $item->excel_name,
            'created_at' => $item->created_at,
        ];
    });
    
    return ResponseApp::success($data);
}
```

## Estructura de Datos

### Tabla `excel_data`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único |
| `value` | text | Todas las columnas concatenadas con " \| " (para búsqueda) |
| `row_data` | json | Array JSON con todas las columnas de la fila |
| `excel_name` | varchar(255) | Nombre del archivo Excel |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

### Ejemplo de Registro

```json
{
  "id": 1,
  "value": "Jesus | Ramirez | Fernandez | 30 años | México",
  "row_data": ["Jesus", "Ramirez", "Fernandez", "30 años", "México"],
  "excel_name": "Datos de Prueba",
  "created_at": "2026-02-10T12:09:30.000000Z",
  "updated_at": "2026-02-10T12:09:30.000000Z"
}
```

## Ejemplos de Uso

### Ejemplo 1: Buscar por nombre

**Datos en la base**:
```
Fila 1: Jesus | Ramirez | Fernandez | 30 años | México
Fila 2: Maria | Lopez | Garcia | 25 años | España
Fila 3: Jesus | Martinez | Sanchez | 35 años | Argentina
```

**Búsqueda**: "Jesus"

**Resultado**:
```json
[
  {
    "id": 1,
    "value": "Jesus | Ramirez | Fernandez | 30 años | México",
    "row_data": ["Jesus", "Ramirez", "Fernandez", "30 años", "México"],
    "excel_name": "Datos de Prueba"
  },
  {
    "id": 3,
    "value": "Jesus | Martinez | Sanchez | 35 años | Argentina",
    "row_data": ["Jesus", "Martinez", "Sanchez", "35 años", "Argentina"],
    "excel_name": "Datos de Prueba"
  }
]
```

### Ejemplo 2: Buscar por apellido

**Búsqueda**: "Ramirez"

**Resultado**:
```json
[
  {
    "id": 1,
    "value": "Jesus | Ramirez | Fernandez | 30 años | México",
    "row_data": ["Jesus", "Ramirez", "Fernandez", "30 años", "México"],
    "excel_name": "Datos de Prueba"
  }
]
```

### Ejemplo 3: Buscar por país

**Búsqueda**: "México"

**Resultado**:
```json
[
  {
    "id": 1,
    "value": "Jesus | Ramirez | Fernandez | 30 años | México",
    "row_data": ["Jesus", "Ramirez", "Fernandez", "30 años", "México"],
    "excel_name": "Datos de Prueba"
  }
]
```

## Pruebas Realizadas

### Prueba 1: Crear datos de prueba
```bash
docker-compose exec php php artisan tinker
```

```php
$testData = [
    ['Jesus', 'Ramirez', 'Fernandez', '30 años', 'México'],
    ['Maria', 'Lopez', 'Garcia', '25 años', 'España'],
    ['Jesus', 'Martinez', 'Sanchez', '35 años', 'Argentina'],
];

foreach ($testData as $row) {
    $searchableValue = implode(' | ', $row);
    App\Models\ExcelData::create([
        'value' => $searchableValue,
        'row_data' => $row,
        'excel_name' => 'Datos de Prueba',
    ]);
}
```

### Prueba 2: Buscar datos
```php
// Buscar "Jesus"
$results = App\Models\ExcelData::where('value', 'LIKE', '%Jesus%')->get();
// Devuelve 2 filas completas

// Buscar "Ramirez"
$results = App\Models\ExcelData::where('value', 'LIKE', '%Ramirez%')->get();
// Devuelve 1 fila completa

// Buscar "México"
$results = App\Models\ExcelData::where('value', 'LIKE', '%México%')->get();
// Devuelve 1 fila completa
```

## Ventajas del Nuevo Sistema

✅ **Búsqueda en todas las columnas**: Busca en cualquier columna de la fila
✅ **Devuelve fila completa**: No solo la columna donde encontró el match
✅ **Búsqueda con múltiples palabras**: Puedes buscar "jesus ramirez" y encontrará filas que contengan ambas palabras
✅ **Lógica AND**: Cuando buscas múltiples palabras, encuentra filas que contengan TODAS las palabras
✅ **Mejor experiencia de usuario**: El usuario ve toda la información relacionada
✅ **Mantiene contexto**: Todas las columnas de la fila se mantienen juntas
✅ **Flexible**: Funciona con cualquier número de columnas
✅ **Eficiente**: Usa índices para búsquedas rápidas

## Comandos Útiles

### Ver estructura de la tabla
```bash
docker-compose exec php php artisan tinker --execute="
\$columns = DB::select('DESCRIBE excel_data');
foreach(\$columns as \$col) {
    echo \$col->Field . ' (' . \$col->Type . ')' . PHP_EOL;
}
"
```

### Probar búsqueda
```bash
docker-compose exec php php artisan tinker --execute="
\$results = App\Models\ExcelData::where('value', 'LIKE', '%Jesus%')->get();
foreach (\$results as \$result) {
    echo 'Fila: ' . implode(' - ', \$result->row_data) . PHP_EOL;
}
"
```

### Limpiar datos de prueba
```bash
docker-compose exec php php artisan tinker --execute="
App\Models\ExcelData::where('excel_name', 'Datos de Prueba')->delete();
echo 'Datos de prueba eliminados' . PHP_EOL;
"
```

## Migración de Datos Existentes

Si tienes datos antiguos que solo tienen el campo `value` sin `row_data`, puedes migrarlos con:

```php
// Este script NO es necesario ahora porque la tabla está vacía
// Pero lo incluyo por si lo necesitas en el futuro

$oldRecords = ExcelData::whereNull('row_data')->get();

foreach ($oldRecords as $record) {
    // Intentar separar el value en columnas
    $columns = explode(' | ', $record->value);
    
    if (count($columns) > 1) {
        $record->row_data = $columns;
        $record->save();
    }
}
```

## Notas Importantes

⚠️ **Datos antiguos**: Los datos que se subieron antes de esta actualización solo tienen el campo `value` sin `row_data`. Para que funcionen correctamente, necesitas volver a subirlos.

✅ **Nuevos archivos**: Todos los archivos Excel que subas a partir de ahora guardarán automáticamente la fila completa.

✅ **Compatibilidad**: El sistema es compatible con archivos Excel de cualquier número de columnas.

## Búsqueda con Múltiples Palabras

### Cómo Funciona

El sistema ahora divide el texto de búsqueda en palabras individuales y busca filas que contengan **TODAS** las palabras (lógica AND).

**Ejemplos**:

1. **Búsqueda con una palabra**: `"jesus"`
   - Encuentra todas las filas que contengan "jesus"

2. **Búsqueda con dos palabras**: `"jesus ramirez"`
   - Divide en: `["jesus", "ramirez"]`
   - Encuentra filas que contengan "jesus" **Y** "ramirez"

3. **Búsqueda con tres palabras**: `"jesus ramirez fernandez"`
   - Divide en: `["jesus", "ramirez", "fernandez"]`
   - Encuentra filas que contengan las 3 palabras

### Código de Implementación

```php
// Dividir el valor de búsqueda en palabras individuales
$searchTerms = array_filter(explode(' ', trim($value)));

// Crear la consulta base
$query = ExcelData::query();

// Agregar condiciones WHERE para cada palabra (lógica AND)
foreach ($searchTerms as $term) {
    $query->where('value', 'LIKE', '%' . $term . '%');
}

$data = $query->orderBy('value', 'asc')
    ->select('id', 'value', 'row_data', 'excel_name', 'created_at')
    ->paginate($pageSize, ['*'], 'page', $current);
```

## Endpoint API

### Búsqueda de Datos

**Una palabra**:
```
GET /admin/search-data?value=Jesus&pageSize=100&current=1
```

**Múltiples palabras**:
```
GET /admin/search-data?value=Jesus%20Ramirez&pageSize=100&current=1
```

**Respuesta**:
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "value": "Jesus | Ramirez | Fernandez | 30 años | México",
        "row_data": ["Jesus", "Ramirez", "Fernandez", "30 años", "México"],
        "excel_name": "Datos de Prueba",
        "created_at": "2026-02-10T12:09:30.000000Z"
      }
    ],
    "per_page": 100,
    "total": 1
  },
  "message": []
}
```
