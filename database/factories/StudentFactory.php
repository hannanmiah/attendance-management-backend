<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'student_id' => (string) $this->faker->unique()->numerify('##########'),
            'class' => $this->faker->randomElement(['10', '11', '12']),
            'section' => $this->faker->randomElement(['A', 'B', 'C']),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}
