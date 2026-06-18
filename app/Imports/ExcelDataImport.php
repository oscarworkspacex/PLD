<?php

namespace App\Imports;

use App\Models\ExcelData;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Log;

class ExcelDataImport implements ToArray, WithChunkReading, WithBatchInserts
{
    protected string $excelName;
    protected string $tipo;
    protected array $existingValues;

    public function __construct(string $excelName, string $tipo = "datos")
    {
        $this->excelName = $excelName;
        $this->tipo = $tipo;
        $this->existingValues = ExcelData::where("excel_name", $this->excelName)
            ->pluck("value")
            ->toArray();
    }

    public function array(array $rows): void
    {
        $dataToInsert = [];
        set_time_limit(0);

        foreach ($rows as $row) {
            $rowData = array_filter($row, function ($value) {
                return $value !== null && $value !== "";
            });

            if (!empty($rowData)) {
                $normalizedRow = array_map(function ($value) {
                    return is_numeric($value) ? (string) $value : $value;
                }, $rowData);

                $searchableValue = implode(" | ", $normalizedRow);

                if (!in_array($searchableValue, $this->existingValues, true)) {
                    $dataToInsert[] = [
                        "value"      => $searchableValue,
                        "row_data"   => json_encode(array_values($normalizedRow)),
                        "excel_name" => $this->excelName,
                        "tipo"       => $this->tipo,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];

                    $this->existingValues[] = $searchableValue;
                }
            }
        }

        if (!empty($dataToInsert)) {
            foreach (array_chunk($dataToInsert, 1000) as $chunk) {
                ExcelData::insert($chunk);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
