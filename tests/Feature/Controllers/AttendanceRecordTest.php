<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AttendanceRecordTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmployeerSeeder']);
        Artisan::call('db:seed', ['--class' => 'AttendanceRecordSeeder']);
    }

    public function testRegisterAttendance()
    {
        $user = User::where('role', 'employee')->first();
        $this->actingAs($user);

        $response = $this->postJson('/api/attendance');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Attendance registered successfully.',
            ]);

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
        ]);
    }

    public function testRegisterAttendanceUnauthenticated()
    {
        $response = $this->postJson('/api/attendance');

        $response->assertStatus(401);
    }

    public function testListAttendanceWithFilters()
    {
        $admin = User::where('role', 'admin')->first();
        $employee1 = User::where('role', 'employee')->first();
        $employee2 = User::where('role', 'employee')->skip(1)->first();

        $this->actingAs($admin);

        $response = $this->getJson('/api/attendance', [
            'start_date' => now()->subDays(3)->toDateString(),
            'end_date' => now()->toDateString(),
        ]);

        $response->assertStatus(200);
    }

    public function testListAttendanceWithoutFilters()
    {
        $admin = User::where('role', 'admin')->first();
        $employee = User::where('role', 'employee')->first();

        $this->actingAs($admin);

        $response = $this->getJson('/api/attendance');

        $response->assertStatus(200);
    }

    public function testListAttendanceUnauthorizedUser()
    {
        $user = User::where('role', 'employee')->first();
        $this->actingAs($user);

        $response = $this->getJson('/api/attendance');

        $response->assertStatus(403);
    }
}
