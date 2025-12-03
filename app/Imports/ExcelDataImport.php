<?php

namespace App\Imports;

use App\Models\ExcelData;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Log;

class ExcelDataImport implements ToArray, WithChunkReading, WithBatchInserts
{
    protected $excelName;
    protected $existingValues;

    public function __construct($excelName)
    {
        $this->excelName = $excelName;
        // Cargar todos los valores existentes para este excel_name una sola vez
        $this->existingValues = ExcelData::where('excel_name', $this->excelName)
            ->pluck('value')
            ->toArray();
    }

    /**
     * Procesa el array de datos del Excel
     */
    public function array(array $rows): void
    {
        $dataToInsert = [];
        set_time_limit(0);
        foreach ($rows as $row) {
            // Procesa cada celda de la fila
            foreach ($row as $cellValue) {
                // Solo inserta si el valor no está vacío
                if ($cellValue !== null && $cellValue !== '') {
                    $normalizedValue = is_numeric($cellValue) ? (string)$cellValue : $cellValue;
                    
                    // Verificar si el valor ya existe (búsqueda en memoria, más rápido)
                    if (!in_array($normalizedValue, $this->existingValues, true)) {
                        $dataToInsert[] = [
                            'value' => $normalizedValue,
                            'excel_name' => $this->excelName,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        // Agregar a la lista de existentes para evitar duplicados en el mismo batch
                        $this->existingValues[] = $normalizedValue;
                    }
                }
            }
        }

        // Insertar en lotes para mejor rendimiento
        if (!empty($dataToInsert)) {
            // Dividir en chunks de 1000 para evitar problemas de memoria
            $chunks = array_chunk($dataToInsert, 1000);
            
            foreach ($chunks as $chunk) {
                ExcelData::insert($chunk);
            }
        }
    }

    /**
     * Define el tamaño del chunk para leer el archivo
     */
    public function chunkSize(): int
    {
        return 1000; // Lee 1000 filas a la vez
    }

    /**
     * Define el tamaño del batch para insertar en la DB
     */
    public function batchSize(): int
    {
        return 1000; // Inserta 1000 registros a la vez
    }
}