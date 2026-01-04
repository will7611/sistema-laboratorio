<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePacienteRequest extends FormRequest
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
        // Si estás usando Route::resource('pacientes', ...),
        // en update Laravel inyecta {paciente}
        $pacienteId = optional($this->route('paciente'))->id;
        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            // Bolivia: 8 dígitos + complemento opcional con guion: 12345678 o 12345678-1B
            'ci' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{7,8}(-[A-Za-z0-9]{1,3})?$/',
                Rule::unique('pacientes', 'ci')->ignore($pacienteId),
            ],

            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],

            // Email opcional pero único si existe
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('pacientes', 'email')->ignore($pacienteId),
            ],

            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:0,1'],
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
