<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'employeer',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'role' => $this->user->role,
                'birth_date' => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
                'cpf' => $this->cpf,
                'cep' => $this->cep,
                'address' => $this->address,
                'number' => $this->number,
                'state' => $this->state,
                'city' => $this->city,
                'manager_id' => $this->manager_id ? $this->manager_id : 0,
                'created_at' => $this->created_at->format('d/m/Y H:i:s'),
                'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
            ],
            'relationships' => [
                'user' => $this->whenLoaded('user', function () {
                    return [
                        'id' => $this->user->id,
                        'role' => $this->user->role,
                    ];
                }),
            ],
            'links' => [
                'self' => route('employees.show', ['id' => $this->id]),
            ],
        ];
    }
}
