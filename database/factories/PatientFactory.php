<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'middlename' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'birthdate' => fake()->date(),
            'address' => fake()->address(),
            'gender' => fake()->randomElement(['male', 'female']),
            'civil_status' => fake()->randomElement(['Single', 'Married', 'Divorced']),
            'religion' => fake()->randomElement(['Catholic', 'Islam', 'INC', 'Others']),
            'occupation' => fake()->jobTitle(),
        ];
    }
}
