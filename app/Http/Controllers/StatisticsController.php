<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Flowframe\Trend\Trend;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function attendanceThisMonth()
    {
        $trend = Trend::query(Attendance::query()->where('status','present'))
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
        return response()->json([
            'attendance' => $trend,
        ]);
    }

    public function attendanceThisYear()
    {
        $trend = Trend::query(Attendance::query()->where('status','present'))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
        return response()->json([
            'attendance' => $trend,
        ]);
    }
}
