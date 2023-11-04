<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->name(),
            "description" => $this->faker->text(),
            "address" => $this->faker->address(),
             "coordinate_lat" => $this->faker->latitude(11.562108, 	11.548148613717737),
            "coordinate_long" => $this->faker->longitude(104.90282871249293, 104.94239872147962),
            "views" => $this->faker->numberBetween(0, 100),
            "area" => $this->faker->numberBetween(0, 100),
            "images" => $this->faker->imageUrl(),
        ];
    }
}
