<?php

namespace App\Actions;

use App\Models\AlertaListaNegra;
use App\Models\ExcelData;
use Illuminate\Support\Facades\Log;

class CheckListaNegraAction
{
    /**
     * Cruza el Excel recién subido contra las listas negras o datos existentes
     * y genera alertas por cada coincidencia exacta encontrada.
     */
    public function execute(string $excelNameSubido, string $tipoSubido): int
    {
        if ($tipoSubido === "datos") {
            return $this->cruzarDatosContraListasNegras($excelNameSubido);
        }

        return $this->cruzarListaNegraContraDatos($excelNameSubido);
    }

    /**
     * Cuando se sube un Excel de datos: busca sus valores en todas las listas negras
     */
    private function cruzarDatosContraListasNegras(string $excelNameDatos): int
    {
        $valoresDatos = ExcelData::where("excel_name", $excelNameDatos)
            ->where("tipo", "datos")
            ->select("id", "value", "row_data")
            ->get();

        if ($valoresDatos->isEmpty()) {
            return 0;
        }

        $listasNegras = ExcelData::where("tipo", "lista_negra")
            ->select("value", "excel_name")
            ->get()
            ->groupBy("excel_name");

        if ($listasNegras->isEmpty()) {
            return 0;
        }

        $alertasCreadas = 0;

        foreach ($valoresDatos as $filaDatos) {
            foreach ($listasNegras as $nombreLista => $registrosLista) {
                $encontrado = $registrosLista->contains(function ($registroLista) use ($filaDatos) {
                    return $this->coincideExacto($filaDatos->value, $registroLista->value);
                });

                if ($encontrado && !$this->existeAlerta($excelNameDatos, $filaDatos->value, $nombreLista)) {
                    AlertaListaNegra::create([
                        "excel_name_origen" => $excelNameDatos,
                        "valor_detectado"   => $filaDatos->value,
                        "row_data"          => $filaDatos->row_data,
                        "nombre_lista"      => $nombreLista,
                    ]);
                    $alertasCreadas++;
                }
            }
        }

        Log::info("CheckListaNegraAction: {$alertasCreadas} alertas generadas para Excel {}");

        return $alertasCreadas;
    }

    /**
     * Cuando se sube una lista negra: busca sus valores en todos los Excels de datos existentes
     */
    private function cruzarListaNegraContraDatos(string $excelNameLista): int
    {
        $valoresLista = ExcelData::where("excel_name", $excelNameLista)
            ->where("tipo", "lista_negra")
            ->pluck("value")
            ->toArray();

        if (empty($valoresLista)) {
            return 0;
        }

        $excelsData = ExcelData::where("tipo", "datos")
            ->select("id", "value", "row_data", "excel_name")
            ->get()
            ->groupBy("excel_name");

        if ($excelsData->isEmpty()) {
            return 0;
        }

        $alertasCreadas = 0;

        foreach ($excelsData as $nombreExcel => $registrosDatos) {
            foreach ($registrosDatos as $filaDatos) {
                foreach ($valoresLista as $valorLista) {
                    if ($this->coincideExacto($filaDatos->value, $valorLista)
                        && !$this->existeAlerta($nombreExcel, $filaDatos->value, $excelNameLista)) {
                        AlertaListaNegra::create([
                            "excel_name_origen" => $nombreExcel,
                            "valor_detectado"   => $filaDatos->value,
                            "row_data"          => $filaDatos->row_data,
                            "nombre_lista"      => $excelNameLista,
                        ]);
                        $alertasCreadas++;
                    }
                }
            }
        }

        Log::info("CheckListaNegraAction: {$alertasCreadas} alertas generadas al subir lista {}");

        return $alertasCreadas;
    }

    /**
     * Coincidencia exacta: compara los valores normalizados (sin espacios extras, sin distinción de mayúsculas)
     */
    private function coincideExacto(string $valorDatos, string $valorLista): bool
    {
        return mb_strtolower(trim($valorDatos)) === mb_strtolower(trim($valorLista));
    }

    /**
     * Evita duplicar alertas para la misma combinación de Excel origen + valor + lista
     */
    private function existeAlerta(string $excelNameOrigen, string $valorDetectado, string $nombreLista): bool
    {
        return AlertaListaNegra::where("excel_name_origen", $excelNameOrigen)
            ->where("valor_detectado", $valorDetectado)
            ->where("nombre_lista", $nombreLista)
            ->exists();
    }
}
