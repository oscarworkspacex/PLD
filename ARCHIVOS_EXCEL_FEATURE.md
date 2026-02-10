# Gestión de Archivos Excel - Documentación

## Descripción
Nueva funcionalidad agregada al panel de administración para gestionar los archivos Excel que han sido subidos al sistema.

## Fecha de Implementación
10 de Febrero, 2026

## Archivos Modificados/Creados

### 1. Nueva Vista
- **Archivo**: `resources/js/pages/admin/excel/Files.vue`
- **Descripción**: Página Vue que muestra la lista de archivos Excel subidos
- **Características**:
  - Lista de archivos con nombre y número de registros
  - Fecha de subida formateada (relativa: "hoy", "ayer", "hace X días")
  - Botón para eliminar archivos
  - Modal de confirmación antes de eliminar
  - Mensajes de éxito/error
  - Diseño moderno consistente con el resto de la aplicación

### 2. Controlador
- **Archivo**: `app/Http/Controllers/Admin/ExcelController.php`
- **Métodos agregados**:
  - `files()`: Renderiza la página de archivos
  - `listFiles()`: API que devuelve la lista de archivos con estadísticas
  - `deleteFile()`: API que elimina un archivo y todos sus registros

### 3. Rutas
- **Archivo**: `routes/admin.php`
- **Rutas agregadas**:
  - `GET /admin/excel/files` → Página de gestión de archivos
  - `GET /admin/excel/list` → API para obtener lista de archivos
  - `DELETE /admin/excel/delete` → API para eliminar archivos

### 4. Sidebar
- **Archivo**: `resources/js/components/AppSidebar.vue`
- **Cambios**: 
  - Agregado nuevo item "Archivos subidos" con icono `FolderOpen`
  - Usa el mismo permiso que "Subir excel" (`Excel read`)

### 5. Rutas TypeScript
- **Archivo**: `resources/js/routes/admin/excel/index.ts`
- **Generado automáticamente** con `php artisan wayfinder:generate`

## Endpoints API

### Listar Archivos
```
GET /admin/excel/list
```
**Respuesta**:
```json
{
  "success": true,
  "data": [
    {
      "excel_name": "Listas GAFI",
      "records_count": 58,
      "created_at": "2026-01-30 14:09:00"
    }
  ]
}
```

### Eliminar Archivo
```
DELETE /admin/excel/delete
Content-Type: application/json

{
  "excel_name": "Listas GAFI"
}
```
**Respuesta**:
```json
{
  "success": true,
  "data": {
    "deleted_count": 58
  },
  "message": ["Archivo 'Listas GAFI' eliminado correctamente. Se eliminaron 58 registros."]
}
```

## Permisos
La funcionalidad usa el permiso existente `Excel read`. Los usuarios con este permiso pueden:
- Ver la lista de archivos subidos
- Eliminar archivos

Los usuarios con rol `root` tienen acceso automático.

## Base de Datos
La funcionalidad trabaja con la tabla `excel_data` existente:
- **Columnas usadas**: `excel_name`, `created_at`
- **Operaciones**: SELECT (agrupado), DELETE

## Cómo Usar

1. **Acceder a la página**:
   - Iniciar sesión en la aplicación
   - En el sidebar, hacer clic en "Archivos subidos"

2. **Ver archivos**:
   - La página muestra todos los archivos Excel que han sido procesados
   - Para cada archivo se muestra:
     - Nombre del archivo
     - Número de registros
     - Fecha de subida

3. **Eliminar un archivo**:
   - Hacer clic en el botón "Eliminar" del archivo deseado
   - Confirmar la eliminación en el modal
   - El sistema eliminará el archivo y todos sus registros asociados

## Comandos Útiles

### Regenerar rutas TypeScript
```bash
docker-compose exec php php artisan wayfinder:generate
```

### Compilar assets
```bash
npm run build
```

### Limpiar caché
```bash
docker-compose exec php php artisan optimize:clear
```

## Notas Técnicas

- La eliminación de archivos es **irreversible**
- Al eliminar un archivo, se eliminan **todos los registros** asociados
- La consulta de archivos agrupa por `excel_name` y cuenta registros
- El frontend usa Axios para las peticiones HTTP
- Los assets se compilan con Vite

## URL de Acceso
```
http://localhost:7654/admin/excel/files
```

## Diseño
El diseño sigue el mismo estilo visual que el resto de la aplicación:
- Fondo degradado oscuro (slate-950 → slate-900)
- Efectos radiales con colores cyan, indigo y emerald
- Bordes con transparencia
- Efectos hover en los elementos
- Animaciones suaves
- Modal con backdrop blur
