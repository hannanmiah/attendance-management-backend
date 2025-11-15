<?php

use App\Models\Student;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
});

test('can get all students', function () {
    Student::factory()->count(3)->create();

    $response = $this->getJson('/api/students');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('can create a student', function () {
    $studentData = [
        'name' => 'John Doe',
        'student_id' => '12345',
        'class' => '10',
        'section' => 'A',
    ];

    $response = $this->postJson('/api/students', $studentData);

    $response->assertStatus(201)
        ->assertJsonFragment($studentData);

    $this->assertDatabaseHas('students', $studentData);
});

test('can get a single student', function () {
    $student = Student::factory()->create();

    $response = $this->getJson("/api/students/{$student->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => $student->name]);
});

test('can update a student', function () {
    $student = Student::factory()->create();

    $updateData = [
        'name' => 'Jane Doe',
        'student_id' => $student->student_id,
        'class' => $student->class,
        'section' => $student->section,
    ];

    $response = $this->putJson("/api/students/{$student->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('students', ['id' => $student->id, 'name' => 'Jane Doe']);
});

test('can delete a student', function () {
    $student = Student::factory()->create();

    $response = $this->deleteJson("/api/students/{$student->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('students', ['id' => $student->id]);
});
