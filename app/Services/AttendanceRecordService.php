<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceRecordService
{
    /**
     * Register attendance for a given user.
     */
    public function registerAttendance(): AttendanceRecord
    {
        try {
            $user = Auth::user();
            DB::beginTransaction();

            $attendanceRecord = AttendanceRecord::create([
                'user_id' => $user->id,
                'registered_at' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $attendanceRecord;
    }

    /**
     * List attendance records for admins, filtered by date range.
     */
    public function listAttendance(array $filters)
{
    $query = AttendanceRecord::query()
        ->join('users as u', 'attendance_records.user_id', '=', 'u.id')
        ->join('employeers as e', 'u.id', '=', 'e.user_id')
        ->leftJoin('employeers as managers', 'e.manager_id', '=', 'managers.id')
        ->leftJoin('users as manager_users', 'managers.user_id', '=', 'manager_users.id')
        ->select([
            'attendance_records.id',
            'u.name as employee_name',
            'u.role as employee_role',
            DB::raw('TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) as age'),
            'manager_users.name as manager_name',
            'attendance_records.registered_at',
        ])
        ->orderBy('attendance_records.registered_at', 'asc');

    if (!empty($filters['start_date'])) {
        $query->where('attendance_records.registered_at', '>=', $filters['start_date']);
    }

    if (!empty($filters['end_date'])) {
        $query->where('attendance_records.registered_at', '<=', $filters['end_date']);
    }

    $query->where('u.role', 'employee');

    return $query->get();
}


}
