<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ExcelDataImport;
use App\Models\ExcelData;
use App\Models\ResponseApp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/excel/Index');
    }
    public function upload(Request $request)
    {
        set_time_limit(0);
        if (!$request->has('file')) {
            return ResponseApp::error(
                [],
                ['El archivo no se recibió en la petición.'],
                422
            );
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                $errorMessage = $file ? $file->getErrorMessage() : 'El archivo no se recibió correctamente.';
                Log::error('Archivo no válido', [
                    'error' => $errorMessage,
                ]);
                return ResponseApp::error(
                    [],
                    [$errorMessage . ' Verifique que el archivo no esté corrupto y que su conexión sea estable.'],
                    422
                );
            }
        }
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // Máximo 20MB
            ], [
                'file.required' => 'Debe seleccionar un archivo.',
                'file.file' => 'El archivo no es válido.',
                'file.mimes' => 'El archivo debe ser de tipo: xlsx, xls o csv.',
                'file.max' => 'El archivo no debe exceder 20MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', [
                'errors' => $e->errors(),
            ]);
            return ResponseApp::error(
                [],
                $e->errors(),
                422
            );
        }

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $uniqueName = $fileName;
            Excel::import(
                new ExcelDataImport($uniqueName),
                $file
            );
            return ResponseApp::success([
                'excel_name' => $uniqueName
            ], ["Archivo procesado correctamente"]);

        } catch (\Exception $e) {
            Log::error('Error al procesar Excel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ResponseApp::error(
                [],
                ['Error al procesar el archivo: ' . $e->getMessage()],
                500
            );
        }
    }
    public function searchData(Request $request)
    {
        $value = $request['value'];
        $pageSize = 100;
        if ($request->has('pageSize')) {
            $pageSize = $request['pageSize'];
        }
        $current = 1;
        if ($request->has('current')) {
            $current = $request['current'];
        }
        
        // Dividir el valor de búsqueda en palabras individuales
        $searchTerms = array_filter(explode(' ', trim($value)));
        
        // Crear la consulta base
        $query = ExcelData::query();
        
        // Agregar condiciones WHERE para cada palabra
        // Esto permite buscar "jesus ramirez" y encontrar filas que contengan ambas palabras
        foreach ($searchTerms as $term) {
            $query->where('value', 'LIKE', '%' . $term . '%');
        }
        
        $data = $query->orderBy('value', 'asc')
            ->select('id', 'value', 'row_data', 'excel_name', 'created_at')
            ->paginate($pageSize, ['*'], 'page', $current);
        
        // Transformar los resultados para incluir la fila completa
        $data->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'value' => $item->value, // Valor concatenado original
                'row_data' => $item->row_data, // Array con todas las columnas de la fila
                'excel_name' => $item->excel_name,
                'created_at' => $item->created_at,
            ];
        });
        
        return ResponseApp::success($data);
    }

    public function files()
    {
        return Inertia::render('admin/excel/Files');
    }

    public function preview(Request $request)
    {
        $excelName = trim((string) $request->query('excel_name', ''));

        if ($excelName === '') {
            abort(404, 'Archivo no encontrado.');
        }

        return Inertia::render('admin/excel/FilePreview', [
            'excelName' => $excelName,
        ]);
    }

    public function previewRows(Request $request)
    {
        $validated = $request->validate([
            'excel_name' => ['required', 'string'],
            'current' => ['nullable', 'integer', 'min:1'],
            'pageSize' => ['nullable', 'integer', 'min:10', 'max:1000'],
        ]);

        $excelName = trim((string) $validated['excel_name']);
        $current = (int) ($validated['current'] ?? 1);
        $pageSize = (int) ($validated['pageSize'] ?? 100);

        $rows = ExcelData::query()
            ->where('excel_name', $excelName)
            ->orderBy('id')
            ->select('id', 'row_data', 'created_at')
            ->paginate($pageSize, ['*'], 'page', $current);

        $rows->getCollection()->transform(function (ExcelData $item) {
            return [
                'id' => $item->id,
                'row_data' => is_array($item->row_data) ? array_values($item->row_data) : [],
                'created_at' => $item->created_at,
            ];
        });

        return ResponseApp::success([
            'excel_name' => $excelName,
            'rows' => $rows,
        ]);
    }

    public function listFiles()
    {
        try {
            $files = ExcelData::select('excel_name')
                ->selectRaw('COUNT(*) as records_count')
                ->selectRaw('MIN(created_at) as created_at')
                ->groupBy('excel_name')
                ->orderBy('created_at', 'desc')
                ->get();

            return ResponseApp::success($files);
        } catch (\Exception $e) {
            Log::error('Error al listar archivos', [
                'message' => $e->getMessage(),
            ]);
            return ResponseApp::error(
                [],
                ['Error al obtener la lista de archivos'],
                500
            );
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $request->validate([
                'excel_name' => 'required|string',
            ]);

            $excelName = $request->input('excel_name');
            
            $deletedCount = ExcelData::where('excel_name', $excelName)->delete();

            if ($deletedCount > 0) {
                return ResponseApp::success(
                    ['deleted_count' => $deletedCount],
                    ["Archivo '{$excelName}' eliminado correctamente. Se eliminaron {$deletedCount} registros."]
                );
            } else {
                return ResponseApp::error(
                    [],
                    ['No se encontró el archivo especificado'],
                    404
                );
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo', [
                'message' => $e->getMessage(),
                'excel_name' => $request->input('excel_name'),
            ]);
            return ResponseApp::error(
                [],
                ['Error al eliminar el archivo: ' . $e->getMessage()],
                500
            );
        }
    }
}