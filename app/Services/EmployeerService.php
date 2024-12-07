<?php
// App\Services\EmployeerService.php

namespace App\Services;

use App\Models\Employeer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmployeerService extends BaseService
{
    public function __construct(
        protected Employeer $employeer,
        protected User $user
    )
    {
        parent::__construct($employeer);
    }

    public function findEmployeerWithUser($id)
    {
        return $this->employeer->where('id', $id)
            ->with('user')
            ->first();
    }

     
    public function getEmployeer(int $id)
    {
        $user = Auth::user();

        if ($user->role === 'employee' && $user->id !== $id) {
            throw new \Exception('Access denied: You can only view your own data.');
        }
        
        return Employeer::with('user')->findOrFail($id);
    }

    public function getAllEmployeersWithUsers()
    {
        return $this->employeer->with('user')->get();
    }

    public function createWithUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role' => $data['role']
        ]);
        $employeer = $this->employeer->create([
            'user_id' => $user->id,
            'name' => $data['birth_date'],
            'cpf' => $data['cpf'],
            'cep' => $data['cep'],
            'address' => $data['address'],
            'number' => $data['number'],
            'state' => $data['state'],
            'city' => $data['city'],
            'manager_id' => $data['manager_id'],
        ]);

        return $employeer;
    }
}
