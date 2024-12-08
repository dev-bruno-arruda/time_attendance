<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceRecordResource;
use App\Services\AttendanceRecordService;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    private $attendanceRecordService;

    public function __construct(AttendanceRecordService $attendanceRecordService)
    {
        $this->attendanceRecordService = $attendanceRecordService;
    }

    /**
     * Register attendance for the authenticated user.
     */
    public function registerAttendance()
    {
        $attendanceRecord = $this->attendanceRecordService->registerAttendance();

        return response()->json([
            'message' => 'Attendance registered successfully.',
            'data' => $attendanceRecord,
        ]);
    }

    /**
     * List attendance records for employees, filtered by date range.
     */
    public function listAttendance(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date']);
        $records = $this->attendanceRecordService->listAttendance($filters);

        return AttendanceRecordResource::collection($records);
    }
}
