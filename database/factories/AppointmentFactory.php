<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'consultation_date' => fake()->date(),
            'blood_pressure' => fake()->randomNumber(3),
            'weight' => fake()->randomNumber(2),
            'heart_rate' => fake()->randomNumber(2),
            'temperature' => fake()->randomNumber(2),
            'chief_complaint' => fake()->text(255),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
