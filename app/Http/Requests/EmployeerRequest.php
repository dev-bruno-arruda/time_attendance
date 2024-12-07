<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'birth_date' => 'nullable|date|before:today',
            'cpf' => 'required|string|size:11|unique:employeers,cpf,' . $this->id,
            'cep' => 'nullable|string|size:8',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'state' => 'nullable|string|size:2',
            'city' => 'nullable|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'The selected user ID does not exist.',

            'birth_date.date' => 'The birth date field must be a valid date.',
            'birth_date.before' => 'The birth date must be a date before today.',

            'cpf.required' => 'The CPF field is required.',
            'cpf.string' => 'The CPF field must be a string.',
            'cpf.size' => 'The CPF field must be exactly 11 characters.',
            'cpf.unique' => 'The CPF is already taken.',

            'cep.string' => 'The CEP field must be a string.',
            'cep.size' => 'The CEP field must be exactly 8 characters.',

            'address.string' => 'The address field must be a string.',
            'address.max' => 'The address field must be less than 255 characters.',

            'number.string' => 'The number field must be a string.',
            'number.max' => 'The number field must be less than 10 characters.',

            'state.string' => 'The state field must be a string.',
            'state.size' => 'The state field must be exactly 2 characters.',

            'city.string' => 'The city field must be a string.',
            'city.max' => 'The city field must be less than 100 characters.',

            'manager_id.exists' => 'The selected manager ID does not exist.',
        ];
    }
}
