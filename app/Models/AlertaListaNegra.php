<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertaListaNegra extends Model
{
    protected $fillable = [
        "excel_name_origen",
        "valor_detectado",
        "row_data",
        "nombre_lista",
        "is_leida",
        "leida_at",
    ];

    protected $casts = [
        "row_data" => "array",
        "is_leida" => "boolean",
        "leida_at" => "datetime",
    ];
}
