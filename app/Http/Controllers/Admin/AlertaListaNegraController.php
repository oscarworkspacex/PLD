<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertaListaNegra;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class AlertaListaNegraController extends Controller
{
    public function index(): JsonResponse
    {
        $alertas = AlertaListaNegra::query()
            ->orderByDesc("id")
            ->limit(500)
            ->get()
            ->map(fn (AlertaListaNegra $alerta) => $this->serialize($alerta))
            ->values();

        return response()->json(["data" => $alertas]);
    }

    public function markAsRead(AlertaListaNegra $alerta): JsonResponse
    {
        $alerta->update([
            "is_leida" => true,
            "leida_at" => Carbon::now(),
        ]);

        return response()->json(["data" => $this->serialize($alerta->fresh())]);
    }

    public function destroy(AlertaListaNegra $alerta): JsonResponse
    {
        $alerta->delete();

        return response()->json(["data" => null]);
    }

    private function serialize(AlertaListaNegra $alerta): array
    {
        return [
            "id"                => $alerta->id,
            "excel_name_origen" => $alerta->excel_name_origen,
            "valor_detectado"   => $alerta->valor_detectado,
            "row_data"          => $alerta->row_data,
            "nombre_lista"      => $alerta->nombre_lista,
            "is_leida"          => $alerta->is_leida,
            "leida_at"          => $alerta->leida_at?->toISOString(),
            "created_at"        => $alerta->created_at?->toISOString(),
        ];
    }
}
