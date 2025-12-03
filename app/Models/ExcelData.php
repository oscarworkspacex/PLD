<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
    protected $fillable = [
        'value',
        'excel_name',
    ];

}
