<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientCaptureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tipo_solicitud' => ['nullable', 'string', 'max:100'],
            'medio_contacto' => ['nullable', 'array'],
            'datos_identificacion' => ['nullable', 'array'],
            'datos_laborales' => ['nullable', 'array'],
            'solicitud_operacion' => ['nullable', 'array'],
            'datos_contacto' => ['nullable', 'array'],
            'garantias' => ['nullable', 'array'],
            'pld' => ['nullable', 'array'],
        ];
    }
}
