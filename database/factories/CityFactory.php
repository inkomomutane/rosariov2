<?php

namespace Database\Factories;

use App\Models\city;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class cityFactory extends Factory
{
    protected $model = city::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'province_id' => Province::factory(),
        ];
    }
}
