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
        $employeerId = $this->route('id'); // ID do employeer a ser atualizado

        return [
            'data.attributes.user_id' => 'exists:users,id',
            'data.attributes.name' => 'required|string|max:255',
            'data.attributes.email' => [
                'required',
                'email',
                "unique:users,email,{$employeerId},id", // Ignora o email do employeer atual
            ],
            'data.attributes.role' => 'required|string|in:admin,employee',
            'data.attributes.birth_date' => 'nullable|date|before:today',
            'data.attributes.cpf' => [
                'required',
                'string',
                'size:11',
                'cpf',
                "unique:employeers,cpf,{$employeerId}" // Ignora o CPF do employeer atual
            ],
            'data.attributes.cep' => 'nullable|string|size:8',
            'data.attributes.address' => 'nullable|string|max:255',
            'data.attributes.number' => 'nullable|string|max:10',
            'data.attributes.state' => 'nullable|string|size:2',
            'data.attributes.city' => 'nullable|string|max:100',
            'data.attributes.manager_id' => 'nullable|exists:users,id',
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
            'data.attributes.user_id.exists' => 'The selected user ID is invalid.',
            'data.attributes.name.required' => 'The name is required.',
            'data.attributes.name.string' => 'The name must be a string.',
            'data.attributes.name.max' => 'The name may not be greater than 255 characters.',
            'data.attributes.email.required' => 'The email is required.',
            'data.attributes.email.email' => 'The email must be a valid email address.',
            'data.attributes.email.unique' => 'The email has already been taken.',
            'data.attributes.role.required' => 'The role is required.',
            'data.attributes.role.string' => 'The role must be a string.',
            'data.attributes.role.in' => 'The selected role is invalid.',
            'data.attributes.birth_date.date' => 'The birth date is not a valid date.',
            'data.attributes.birth_date.before' => 'The birth date must be a date before today.',
            'data.attributes.cpf.required' => 'The CPF is required.',
            'data.attributes.cpf.string' => 'The CPF must be a string.',
            'data.attributes.cpf.size' => 'The CPF must be 11 characters.',
            'data.attributes.cpf.unique' => 'The CPF has already been taken.',
            'data.attributes.cpf.cpf' => 'The CPF is not valid.',
            'data.attributes.cep.string' => 'The CEP must be a string.',
            'data.attributes.cep.size' => 'The CEP must be 8 characters.',
            'data.attributes.address.string' => 'The address must be a string.',
            'data.attributes.address.max' => 'The address may not be greater than 255 characters.',
            'data.attributes.number.string' => 'The number must be a string.',
            'data.attributes.number.max' => 'The number may not be greater than 10 characters.',
            'data.attributes.state.string' => 'The state must be a string.',
            'data.attributes.state.size' => 'The state must be 2 characters.',
            'data.attributes.state.uf' => 'The state is not a valid UF.',
            'data.attributes.city.string' => 'The city must be a string.',
            'data.attributes.city.max' => 'The city may not be greater than 100 characters.',
            'data.attributes.manager_id.exists' => 'The selected manager ID is invalid.',
        ];
    }
}
