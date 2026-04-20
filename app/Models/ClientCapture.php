<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientCapture extends Model
{
    protected $guarded = [];

    protected $casts = [
        'datos_identificacion' => 'array',
        'datos_laborales' => 'array',
        'solicitud_operacion' => 'array',
        'datos_contacto' => 'array',
        'pld' => 'array',
    ];

    public function anexos(): HasMany
    {
        return $this->hasMany(ClientCaptureAnexo::class);
    }

    public function latestAnexo(): HasOne
    {
        return $this->hasOne(ClientCaptureAnexo::class)->latestOfMany();
    }
}
