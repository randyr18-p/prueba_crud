<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user');
         return [
            'nombres' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'apellidos' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'email' => [
                'sometimes',
                'required',
                'email:rfc,dns',
                'max:150',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'telefono' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[\d\s\-\+\(\)]+$/',
                'min:7',
                'max:20',
            ],
            'estado' => [
                'sometimes',
                'in:activo,inactivo',
            ],
        ];
    }

     protected function failedValidation(Validator $validator)
    {
        $errors = collect($validator->errors()->toArray())
            ->map(fn($messages) => $messages[0])
            ->toArray();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Se encontraron errores de validación.',
            'errors' => $errors,
        ], 422));
    }
}
