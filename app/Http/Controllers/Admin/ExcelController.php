<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CheckListaNegraAction;
use App\Http\Controllers\Controller;
use App\Imports\ExcelDataImport;
use App\Models\AlertaListaNegra;
use App\Models\ExcelData;
use App\Models\ResponseApp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public function index()
    {
        return Inertia::render("admin/excel/Index");
    }

    public function upload(Request $request, CheckListaNegraAction $checkListaNegra)
    {
        set_time_limit(0);

        if (!$request->has("file")) {
            return ResponseApp::error([], ["El archivo no se recibió en la petición."], 422);
        }

        if ($request->hasFile("file")) {
            $file = $request->file("file");
            if (!$file || !$file->isValid()) {
                $errorMessage = $file ? $file->getErrorMessage() : "El archivo no se recibió correctamente.";
                return ResponseApp::error(
                    [],
                    [$errorMessage . " Verifique que el archivo no esté corrupto y que su conexión sea estable."],
                    422
                );
            }
        }

        try {
            $request->validate([
                "file" => "required|file|mimes:xlsx,xls,csv|max:20480",
                "tipo" => "nullable|in:datos,lista_negra",
            ], [
                "file.required" => "Debe seleccionar un archivo.",
                "file.file"     => "El archivo no es válido.",
                "file.mimes"    => "El archivo debe ser de tipo: xlsx, xls o csv.",
                "file.max"      => "El archivo no debe exceder 20MB.",
                "tipo.in"       => "El tipo de archivo no es válido.",
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ResponseApp::error([], $e->errors(), 422);
        }

        try {
            $file         = $request->file("file");
            $originalName = $file->getClientOriginalName();
            $uniqueName   = pathinfo($originalName, PATHINFO_FILENAME);
            $tipo         = $request->input("tipo", "datos");

            Excel::import(new ExcelDataImport($uniqueName, $tipo), $file);

            $alertasGeneradas = $checkListaNegra->execute($uniqueName, $tipo);

            $mensaje = "Archivo procesado correctamente.";
            if ($alertasGeneradas > 0) {
                $mensaje .= " Se generaron {$alertasGeneradas} alerta(s) en Lista Negra/PEP.";
            }

            return ResponseApp::success(
                ["excel_name" => $uniqueName, "alertas_generadas" => $alertasGeneradas],
                [$mensaje]
            );
        } catch (\Exception $e) {
            Log::error("ERROR AL PROCESAR EXCEL", [
                "message" => $e->getMessage(),
                "file"    => $e->getFile(),
                "line"    => $e->getLine(),
            ]);
            return ResponseApp::error([], ["Error al procesar el archivo: " . $e->getMessage()], 500);
        }
    }

    public function searchData(Request $request)
    {
        $value    = $request["value"];
        $pageSize = $request->has("pageSize") ? (int) $request["pageSize"] : 100;
        $current  = $request->has("current") ? (int) $request["current"] : 1;

        $searchTerms = array_filter(explode(" ", trim($value)));

        // Búsqueda normal en todos los archivos
        $query = ExcelData::query();
        foreach ($searchTerms as $term) {
            $query->where("value", "LIKE", "%" . $term . "%");
        }

        $data = $query->orderBy("value", "asc")
            ->select("id", "value", "row_data", "excel_name", "created_at")
            ->paginate($pageSize, ["*"], "page", $current);

        $data->getCollection()->transform(function ($item) {
            return [
                "id"         => $item->id,
                "value"      => $item->value,
                "row_data"   => $item->row_data,
                "excel_name" => $item->excel_name,
                "created_at" => $item->created_at,
            ];
        });

        // Verificar si el término buscado coincide con entradas en lista_negra.
        // coincidencias: total hallado (sirve para mostrar el badge SIEMPRE que haya match).
        // alertasNuevas: solo las que no existían en BD (evita duplicados).
        $coincidencias = 0;
        $alertasNuevas = 0;

        if (!empty($searchTerms)) {
            $listaNegraQuery = ExcelData::where("tipo", "lista_negra");
            foreach ($searchTerms as $term) {
                $listaNegraQuery->where("value", "LIKE", "%" . $term . "%");
            }
            $listaNegraMatches = $listaNegraQuery->get(["value", "excel_name", "row_data"]);
            $coincidencias     = $listaNegraMatches->count();

            foreach ($listaNegraMatches as $match) {
                $existe = AlertaListaNegra::where("valor_detectado", $match->value)
                    ->where("nombre_lista", $match->excel_name)
                    ->exists();

                if (!$existe) {
                    AlertaListaNegra::create([
                        "excel_name_origen" => "Búsqueda: " . $value,
                        "valor_detectado"   => $match->value,
                        "row_data"          => $match->row_data,
                        "nombre_lista"      => $match->excel_name,
                    ]);
                    $alertasNuevas++;
                }
            }
        }

        return ResponseApp::success([
            "results"           => $data,
            "coincidencias"     => $coincidencias,
            "alertas_generadas" => $alertasNuevas,
        ]);
    }

    public function files()
    {
        return Inertia::render("admin/excel/Files");
    }

    public function preview(Request $request)
    {
        $excelName = trim((string) $request->query("excel_name", ""));

        if ($excelName === "") {
            abort(404, "Archivo no encontrado.");
        }

        return Inertia::render("admin/excel/FilePreview", ["excelName" => $excelName]);
    }

    public function previewRows(Request $request)
    {
        $validated = $request->validate([
            "excel_name" => ["required", "string"],
            "current"    => ["nullable", "integer", "min:1"],
            "pageSize"   => ["nullable", "integer", "min:10", "max:1000"],
        ]);

        $excelName = trim((string) $validated["excel_name"]);
        $current   = (int) ($validated["current"] ?? 1);
        $pageSize  = (int) ($validated["pageSize"] ?? 100);

        $rows = ExcelData::query()
            ->where("excel_name", $excelName)
            ->orderBy("id")
            ->select("id", "row_data", "created_at")
            ->paginate($pageSize, ["*"], "page", $current);

        $rows->getCollection()->transform(function (ExcelData $item) {
            return [
                "id"         => $item->id,
                "row_data"   => is_array($item->row_data) ? array_values($item->row_data) : [],
                "created_at" => $item->created_at,
            ];
        });

        return ResponseApp::success(["excel_name" => $excelName, "rows" => $rows]);
    }

    public function listFiles()
    {
        try {
            $files = ExcelData::select("excel_name", "tipo")
                ->selectRaw("COUNT(*) as records_count")
                ->selectRaw("MIN(created_at) as created_at")
                ->groupBy("excel_name", "tipo")
                ->orderBy("created_at", "desc")
                ->get();

            return ResponseApp::success($files);
        } catch (\Exception $e) {
            Log::error("Error al listar archivos", ["message" => $e->getMessage()]);
            return ResponseApp::error([], ["Error al obtener la lista de archivos"], 500);
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $request->validate(["excel_name" => "required|string"]);

            $excelName    = $request->input("excel_name");
            $deletedCount = ExcelData::where("excel_name", $excelName)->delete();

            if ($deletedCount > 0) {
                return ResponseApp::success(
                    ["deleted_count" => $deletedCount],
                    ["Archivo \"{$excelName}\" eliminado correctamente. Se eliminaron {$deletedCount} registros."]
                );
            }

            return ResponseApp::error([], ["No se encontró el archivo especificado"], 404);
        } catch (\Exception $e) {
            Log::error("Error al eliminar archivo", [
                "message"    => $e->getMessage(),
                "excel_name" => $request->input("excel_name"),
            ]);
            return ResponseApp::error([], ["Error al eliminar el archivo: " . $e->getMessage()], 500);
        }
    }
}
