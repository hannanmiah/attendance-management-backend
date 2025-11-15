<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $this->attendanceService->record($request->validated());

        return response()->json(['message' => 'Attendance recorded successfully.']);
    }

    public function monthlyReport(Request $request)
    {
        $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'class' => ['required', 'string'],
        ]);

        return $this->attendanceService->getMonthlyReport($request->month, $request->class);
    }

    public function statistics()
    {
        return $this->attendanceService->getAttendanceStatistics();
    }
}
