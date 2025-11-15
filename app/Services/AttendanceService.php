<?php

namespace App\Services;

use App\Events\AttendanceRecorded;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AttendanceService
{
    public function record(array $attendanceData): void
    {
        foreach ($attendanceData as $data) {
            Attendance::create([
                'student_id' => $data['student_id'],
                'date' => $data['date'],
                'status' => $data['status'],
                'note' => $data['note'] ?? null,
                'recorded_by' => Auth::id(),
            ]);
        }

        AttendanceRecorded::dispatch($attendanceData);

        Cache::forget('attendance_statistics');
    }

    public function getMonthlyReport(int $month, string $class)
    {
        return Attendance::with('student')
            ->whereMonth('date', $month)
            ->whereHas('student', function ($query) use ($class) {
                $query->where('class', $class);
            })
            ->get();
    }

    public function getAttendanceStatistics()
    {
        return Cache::remember('attendance_statistics', now()->addDay(), function () {
            return [
                'present' => Attendance::where('status', 'present')->count(),
                'absent' => Attendance::where('status', 'absent')->count(),
                'late' => Attendance::where('status', 'late')->count(),
            ];
        });
    }
}
