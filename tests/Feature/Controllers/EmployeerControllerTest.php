<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Employeer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class EmployeerControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_index_retrieves_employeers()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        // Criando alguns empregadores manualmente
        Employeer::create([
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
                         ->getJson('/api/employeers');

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
                    'name' => 'New Employeer',
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
                         ->postJson('/api/employeers', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Employeer created successfully',
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

    public function test_show_retrieves_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employeer::create([
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
                         ->getJson("/api/employeers/{$employeer->id}");

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

    public function test_update_updates_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employeer::create([
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
                         ->putJson("/api/employeers/{$employeer->id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Employeer updated successfully',
                     'status' => 'success',
                 ]);
    }

    public function test_destroy_deletes_employeer()
    {
        $admin = User::where('role', 'admin')->first();
        $token = $admin->createToken('test')->plainTextToken;

        $employeer = Employeer::create([
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
                         ->deleteJson("/api/employeers/{$employeer->id}");

        $response->assertStatus(204);
    }
}
