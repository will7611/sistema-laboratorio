<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $userId = $this->route('user'); // id del usuario que se está editando

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|confirmed|min:6',
            'role' => 'required|array',
            'ci' => 'required|string|max:18|unique:users,ci,' . $userId,
            'phone' => 'nullable|string|max:22',
            'address' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
