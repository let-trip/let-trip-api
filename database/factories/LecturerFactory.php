<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecturer>
 */
class LecturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "phone" => $this->faker->phoneNumber(),
            "telegram" => $this->faker->userName(),
            "description" => $this->faker->text(),
            "quote" => $this->faker->text(),
            "photo" => $this->faker->imageUrl(332,168),
            "note" => $this->faker->text(),
            "student_relation" => $this->faker->text(),
            "skill" => $this->faker->text(),
            "photo_album" => $this->faker->imageUrl(332,168),
        ];
    }
}
