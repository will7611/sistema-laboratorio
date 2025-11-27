<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
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
            'name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'ci' => 'required|max:12',
            'birth_date' => 'nullable|date',
            'age'=> 'nullable|integer',
            'phone' => 'required|max:18',
            'email' => 'required|max:150',
            'address' => 'nullable|max:50',
            'status' => 'nullable|integer'
        ];
    }
}
