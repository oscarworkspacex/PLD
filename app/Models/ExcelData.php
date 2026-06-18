<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
    protected $fillable = [
        'value',
        'tipo',
        'excel_name',
        'row_data',
    ];

    protected $casts = [
        'row_data' => 'array',
    ];
}
