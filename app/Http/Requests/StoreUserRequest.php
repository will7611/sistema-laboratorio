<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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

     protected function prepareForValidation(): void
    {
        // Normaliza CI: sin espacios y en mayúsculas antes de validar
        if ($this->has('ci')) {
            $ci = strtoupper(preg_replace('/\s+/', '', (string) $this->input('ci')));
            $this->merge(['ci' => $ci]);
        }

        // Normaliza email: trim + lower (opcional)
        if ($this->has('email') && $this->input('email') !== null) {
            $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = optional($this->route('user'))->id;
        return [
            'name' => 'required|string|max:255',
             // Email opcional pero único si existe
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'required|confirmed|min:6',
            'role' => 'required|array',
            'role.*' => 'exists:roles,name', 
             'ci' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{7,8}(-[A-Za-z0-9]{1,3})?$/',
                Rule::unique('pacientes', 'ci')->ignore($userId),
            ],
            'phone' => 'nullable|string|max:22',
            'address' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // hasta 2MB
        ];
    }
    public function messages(): array
    {
        return [
            'ci.regex' => 'El CI debe tener 8 dígitos y opcionalmente un complemento. Ej: 12345678 o 12345678-1B.',
            'ci.unique' => 'Este CI ya está registrado.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.email' => 'El correo no tiene un formato válido.',
        ];
    }
}
