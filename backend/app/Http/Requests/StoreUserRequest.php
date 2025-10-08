<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule; 

class StoreUserRequest extends FormRequest
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
            'nombres'=> [
                'required',
                'string',
                'max:255',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'apellidos'=> [
                'required',
                'string',
                'max:255',
                'regex:/^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/',
            ],
            'email' => [
                'required',
                'email:rfc',
                'max:150',
                Rule::unique('users', 'email')->where(fn($q) => $q->where('estado', 'activo')),
            ],
            'telefono' => [
                'required',
                'string',
                'regex:/^[\d\s\-\+\(\)]+$/',
                'min:7',
                'max:20',
            ],
        ];
    }

    public function messages(): array
    {
        return [
           'nombres.required' => 'El nombre es obligatorio',
            'nombres.regex' => 'El nombre solo puede contener letras y espacios',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y espacios',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe tener un formato válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios y los caracteres + - ( )',
        ];
    }

    protected function failedValidation(Validator $validator) {
    $errors = $validator->errors()->toArray();
    $formattedErrors = [];
    foreach ($errors as $field => $messages) {
        // Se toma solamente el primer mensaje por campo para simplificación
        $formattedErrors[$field] = $messages[0];
    }

    throw new HttpResponseException(response()->json([
        'success' => false,
        'message' => 'Se encontraron errores en el formulario. Por favor corríjalos antes de continuar.',
        'errors' => $formattedErrors,
        'help' => 'Revise los campos indicados y verifique que los datos cumplen con los requisitos.',
    ], 422));
}

}
