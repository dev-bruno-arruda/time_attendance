<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Create some users first!');
            return;
        }

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subDays($i)->startOfDay(); // Começa do início do dia atual

                AttendanceRecord::create([
                    'user_id' => $user->id,
                    'registered_at' => $date->copy()->addHours(8), // 08:00 da manhã
                ]);

                AttendanceRecord::create([
                    'user_id' => $user->id,
                    'registered_at' => $date->copy()->addHours(17), // 17:00 da tarde
                ]);
            }
        }
    }
}
