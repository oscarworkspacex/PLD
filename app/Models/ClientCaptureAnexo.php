<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientCaptureAnexo extends Model
{
    protected $guarded = [];

    public function clientCapture(): BelongsTo
    {
        return $this->belongsTo(ClientCapture::class);
    }
}
