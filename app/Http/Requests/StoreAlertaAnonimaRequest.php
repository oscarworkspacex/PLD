<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlertaAnonimaRequest extends FormRequest
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
            'destinatario' => ['nullable', 'string', 'max:255'],
            'asunto' => ['nullable', 'string', 'max:255'],
            'empleado_reportado' => ['nullable', 'string', 'max:255'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'detalle' => ['nullable', 'string'],
            'denuncia' => ['required', 'string'],
        ];
    }
}
