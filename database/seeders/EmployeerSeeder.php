<?php

namespace Database\Seeders;

use App\Models\Employeer;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeerSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::where('email', 'admin@example.com')->first();
        $employeeUser = User::where('email', 'employee@example.com')->first();

        Employeer::create([
            'user_id' => $adminUser->id,
            'birth_date' => '1980-01-01',
            'cpf' => '12345678901',
            'cep' => '01001000',
            'address' => 'Admin Street',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
            'manager_id' => null,
        ]);

        Employeer::create([
            'user_id' => $employeeUser->id,
            'birth_date' => '1990-05-15',
            'cpf' => '98765432100',
            'cep' => '02002000',
            'address' => 'Employee Avenue',
            'number' => '200',
            'state' => 'RJ',
            'city' => 'Rio de Janeiro',
            'manager_id' => $adminUser->id,
        ]);
    }
}
