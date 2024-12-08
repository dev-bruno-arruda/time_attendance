<?php
// App\Services\EmployeeService.php

namespace App\Services;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmployeeService extends BaseService
{
    public function __construct(
        protected Employee $employeer,
    )
    {
        parent::__construct($employeer);
    }

    public function findEmployeeWithUser($id)
    {
        return $this->employeer->where('id', $id)
            ->with('user')
            ->first();
    }

     
    public function getEmployee(int $id)
    {
        $user = Auth::user();

        if (!$this->hasPermission($user, $id)) {
            throw new \Exception('You do not have permission to access this resource', 403);
        }
        
        return Employee::with('user')->findOrFail($id);
    }

    private function hasPermission($user, int $id): bool
    {
        return !($user->role === 'employee' && $user->id !== $id);
    }

    public function getAllEmployeesWithUsers()
    {
        return $this->employeer->with('user')->get();
    }

    public function createWithUser(array $data)
    {
        try
        {
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt(env('DEFAULT_PASSWORD', 'Ticto@123')),
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
            DB::commit();
            return $employeer;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEmployee(array $data, int $id)
    {
        if (!$this->hasPermission(Auth::user(), $id)) {
            throw new \Exception('You do not have permission to access this resource', 403);
        }
        try {
            DB::beginTransaction();
            $employeer = $this->employeer->findOrFail($id);

            $userData = [
                'name' => $data['name'] ?? $employeer->user->name,
                'email' => $data['email'] ?? $employeer->user->email,
                'role' => $data['role'] ?? $employeer->user->role,
            ];

            $employeer->user->update($userData);

            $employeerData = [
                'cpf' => $data['cpf'] ?? $employeer->cpf,
                'birth_date' => $data['birth_date'] ?? $employeer->birth_date,
                'cep' => $data['cep'] ?? $employeer->cep,
                'address' => $data['address'] ?? $employeer->address,
                'number' => $data['number'] ?? $employeer->number,
                'state' => $data['state'] ?? $employeer->state,
                'city' => $data['city'] ?? $employeer->city,
                'manager_id' => $data['manager_id'] ?? $employeer->manager_id,
            ];

            $employeer->update($employeerData);

            DB::commit();
            return $employeer;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Delete a employeer (soft delete) and deactivate the associated user.
     * @param int $id
     * @return void
     */
    public function softDeleteEmployeeAndDeactivateUser(int $id)
    {
        $employeer = Employee::findOrFail($id);

        $employeer->delete();

        if ($employeer->user) {
            $user = $employeer->user;
            $user->is_active = false;
            $user->save();
        }
    }

}
