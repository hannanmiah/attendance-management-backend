<?php

use App\Events\AttendanceRecorded;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->student = Student::factory()->create();
    Sanctum::actingAs($this->user);
});

test('paginate latest attendances',function (){
    Attendance::factory()->count(3)->create();
    $response = $this->getJson('/api/attendances');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
    // assert json structure
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'student_id',
                'date',
                'status',
                'note',
                'recorded_by',
                'student',
            ],
        ],
    ]);
});

test('can record attendance in bulk', function () {
    $students = Student::factory()->count(3)->create();

    $attendanceData = [
        [
            'student_id' => $students[0]->id,
            'date' => '2025-11-15',
            'status' => 'present',
        ],
        [
            'student_id' => $students[1]->id,
            'date' => '2025-11-15',
            'status' => 'absent',
        ],
        [
            'student_id' => $students[2]->id,
            'date' => '2025-11-15',
            'status' => 'late',
        ],
    ];

    $response = $this->postJson('/api/attendance', $attendanceData);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Attendance recorded successfully.']);

    foreach ($attendanceData as $data) {
        $this->assertDatabaseHas('attendances', [
            'student_id' => $data['student_id'],
            'date' => $data['date'],
            'status' => $data['status'],
            'recorded_by' => $this->user->id,
        ]);
    }
});

test('can get monthly attendance report', function () {
    Attendance::factory()->create([
        'student_id' => $this->student->id,
        'date' => '2025-11-15',
    ]);

    $response = $this->getJson('/api/attendance/report?month=11&class='.$this->student->class);

    $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment(['student_id' => $this->student->id]);
});

test('dispatches an event when attendance is recorded', function () {
    Event::fake();

    $attendanceData = [
        [
            'student_id' => $this->student->id,
            'date' => '2025-11-15',
            'status' => 'present',
        ],
    ];

    $this->postJson('/api/attendance', $attendanceData);

    Event::assertDispatched(AttendanceRecorded::class);
});

test('can get attendance statistics', function () {
    Cache::flush();

    Attendance::factory()->create(['status' => 'present', 'student_id' => $this->student->id, 'recorded_by' => $this->user->id]);
    Attendance::factory()->create(['status' => 'absent', 'student_id' => $this->student->id, 'recorded_by' => $this->user->id]);
    Attendance::factory()->create(['status' => 'late', 'student_id' => $this->student->id, 'recorded_by' => $this->user->id]);

    $response = $this->getJson('/api/attendance/statistics');

    $response->assertStatus(200)
        ->assertJson([
            'present' => 1,
            'absent' => 1,
            'late' => 1,
        ]);

    $this->assertTrue(Cache::has('attendance_statistics'));

    $response = $this->getJson('/api/attendance/statistics');
    $response->assertStatus(200);
});
