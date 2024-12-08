<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;
use Mockery;
use App\Services\EmployeeService;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_index_retrieves_employees()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        // Criando alguns empregadores manualmente
        Employee::create([
            'user_id' => $admin->id,
            'cpf' => '22272840051',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson('/api/employees');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'status',
                     'data' => [
                         '*' => [
                             'type',
                             'id',
                             'attributes' => [
                                 'name',
                                 'email',
                                 'role',
                                 'birth_date',
                                 'cpf',
                                 'cep',
                                 'address',
                                 'number',
                                 'state',
                                 'city',
                                 'manager_id',
                                 'created_at',
                                 'updated_at',
                             ],
                             'links' => [
                                 'self',
                             ],
                         ],
                     ],
                 ]);
    }

    public function test_store_creates_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'New Employee',
                    'email' => 'employeertest@example.com',
                    'role' => 'employee',
                    'cpf' => '04391592029',
                    'birth_date' => '1990-05-10',
                    'cep' => '12345000',
                    'address' => 'Rua Nova',
                    'number' => '200',
                    'state' => 'SP',
                    'city' => 'Campinas',
                    'manager_id' => null,
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/employees', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Employee created successfully',
                     'status' => 'success',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'type',
                         'id',
                         'attributes' => [
                             'name',
                             'email',
                             'role',
                             'birth_date',
                             'cpf',
                             'cep',
                             'address',
                             'number',
                             'state',
                             'city',
                             'manager_id',
                             'created_at',
                             'updated_at',
                         ],
                     ],
                 ]);
    }

    /// This test does not count because the exit is by middleware.
    public function test_access_denied_store_employeer()
    {
        $admin = User::where('role', 'employee')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'New Employee',
                    'email' => 'teste@teste.com',
                    'role' => 'employee',
                    'cpf' => '08727329089',
                    'birth_date' => '1990-05-10',
                    'cep' => '12345000',
                    'address' => 'Rua Nova',
                    'number' => '200',
                    'state' => 'SP',
                    'city' => 'Campinas',
                    'manager_id' => null,
                ],
            ],
        ];
        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/employees', $data);
        $response->assertStatus(403);
    }

    public function test_show_retrieves_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employee::create([
            'user_id' => $admin->id,
            'cpf' => '70862571090',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson("/api/employees/{$employeer->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'status',
                     'data' => [
                         'type',
                         'id',
                         'attributes' => [
                             'name',
                             'email',
                             'role',
                             'birth_date',
                             'cpf',
                             'cep',
                             'address',
                             'number',
                             'state',
                             'city',
                             'manager_id',
                             'created_at',
                             'updated_at',
                         ],
                         'links' => [
                             'self',
                         ],
                     ],
                 ]);
    }
    
    public function test_access_denied_show_retrieves_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employee::create([
            'user_id' => $admin->id,
            'cpf' => '70862571090',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->getJson("/api/employees/9999");

        $response->assertStatus(403);
    }

    public function test_update_updates_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employee::create([
            'user_id' => $admin->id,
            'cpf' => '42342334044',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Admin Updated',
                    'email' => 'adminupdated@example.com',
                    'role' => 'admin',
                    'cpf' => '42342334044',
                    'address' => 'Rua Alterada',
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->putJson("/api/employees/{$employeer->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Employee updated successfully',
                     'status' => 'success',
                 ]);
    }
    
    public function test_destroy_deletes_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employee::create([
            'user_id' => $admin->id,
            'cpf' => '51378447050',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->deleteJson("/api/employees/{$employeer->id}");

        $response->assertStatus(204);
    }

    public function test_employeer_not_found_throws_404()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->deleteJson("/api/employees/9999"); // ID inexistente

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Employee not found',
            'status' => 'error',
        ]);
    }

    public function test_employeer_deletion_throws_403()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employee::create([
            'user_id' => $admin->id,
            'cpf' => '42342334044',
            'birth_date' => now()->subYears(30),
            'cep' => '12345000',
            'address' => 'Rua Teste',
            'number' => '100',
            'state' => 'SP',
            'city' => 'São Paulo',
        ]);

        $mockService = Mockery::mock(EmployeeService::class);
        $mockService->shouldReceive('softDeleteEmployeeAndDeactivateUser')
                    ->andThrow(new \Exception('Permission Denied'));

        app()->instance(EmployeeService::class, $mockService);

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->deleteJson("/api/employees/{$employeer->id}");

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'Permission Denied',
            'status' => 'error',
        ]);
    }
}
