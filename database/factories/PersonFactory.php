<?php

namespace Database\Factories;

use App\Enum\Sex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $this->faker->date(),
            'gender' => $this->faker->randomElement(Sex::toValues()),
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'nationality' => $this->faker->country(),
            'province' => $this->faker->city(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'address_one' => $this->faker->streetAddress(),
            'address_two' => $this->faker->streetAddress(),
            'address_three' => $this->faker->streetAddress(),
            'address_four' => $this->faker->streetAddress(),
            'phone_one' => $this->faker->phoneNumber(),
            'phone_two' => $this->faker->phoneNumber(),
            'phone_three' => $this->faker->phoneNumber(),
            'phone_four' => $this->faker->phoneNumber(),
            'death_date' => $this->faker->optional()->date(),
            'cause_of_death' => $this->faker->optional()->sentence(),
            'created_by_name' => $this->faker->name(),
            'created_by_id' => null,
        ];
    }
}
