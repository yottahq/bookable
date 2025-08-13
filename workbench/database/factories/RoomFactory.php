<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Room;

class RoomFactory extends Factory
{
    protected $model = Room::class;
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'capacity' => fake()->numberBetween(1, 100),
            'location' => fake()->address(),
            'amenities' => json_encode(fake()->words(3, true)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
