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
        if (!$request->hasFile('file')) {
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
        $data = ExcelData::where('value', 'LIKE', '%' . $value . '%')
            ->orderBy('value', 'asc')
            ->select('id', 'value', 'excel_name')
            ->paginate($pageSize, ['*'], 'page', $current);
        return ResponseApp::success($data);
    }
}