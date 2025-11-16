<?php

use App\Models\Attendance;
use App\Models\User;

test('get statistics this month', function () {
    // create user
    $user = User::factory()->create();
    // create some attendance
     Attendance::factory()->count(10)->create();
     // hit the endpoint
     $response = $this->actingAs($user)->getJson('/api/stats/attendance-this-month');
     // assert the response
     $response->assertStatus(200);
     // assert json structure
     $response->assertJsonStructure([
         'attendance' => [
             '*' => [
                 'date',
                 'aggregate'
             ],
         ],
     ]);
});

test('get statistics this year', function () {
    // create user
    $user = User::factory()->create();
    // create some attendance
     Attendance::factory()->count(10)->create();
     // hit the endpoint
     $response = $this->actingAs($user)->getJson('/api/stats/attendance-this-year');
     // assert the response
     $response->assertStatus(200);
     // assert json structure
     $response->assertJsonStructure([
         'attendance' => [
             '*' => [
                 'date',
                 'aggregate'
             ],
         ],
     ]);
});

