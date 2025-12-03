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

class ExcelController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/excel/Index');
    }
    public function upload(Request $request)
    {
        // Validar que se haya subido un archivo
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:20480', // Máximo 20MB
        ]);
        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            
            // Generar nombre único para evitar conflictos
            $uniqueName = $fileName;
            
            // Procesar el archivo Excel
            Excel::import(
                new ExcelDataImport($uniqueName),
                $file
            );
            return ResponseApp::success([
                'excel_name' => $uniqueName
            ],["Archivo procesado correctamente"]);

        } catch (\Exception $e) {
            \Log::error($e);
            return ResponseApp::error([],[$e->getMessage()],500);
        }
    }
    public function searchData(Request $request){
        $value = $request['value'];
        $pageSize = 100;
        if($request->has('pageSize')){  
            $pageSize = $request['pageSize'];
        }
        $current = 1;
        if($request->has('current')){
            $current = $request['current'];
        }
        $data = ExcelData::where('value','LIKE','%'.$value.'%')
                    ->orderBy('value','asc')
                    ->select('id','value','excel_name')
                    ->paginate($pageSize,['*'],'page',$current);
        return ResponseApp::success($data);
    }
}
