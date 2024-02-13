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
//                      $table->string('name');
//            $table->string('email');
//            $table->string('phone');
//            $table->string('telegram');
//            $table->string('description');
//            $table->string('quote');
//            $table->string('photo');
//            $table->string('note');
//            $table->string('final_project_title');
//            $table->string('final_project_description');
//            $table->string('skill');
//            $table->string('photo_album');
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->safeEmail(),
            "phone" => $this->faker->phoneNumber(),
            "telegram" => $this->faker->userName(),
            "description" => $this->faker->text(),
            "quote" => $this->faker->text(),
            "photo" => $this->faker->imageUrl(332,168),
            "note" => $this->faker->text(),
            "final_project_title" => $this->faker->text(),
            "final_project_description" => $this->faker->text(),
            "skill" => $this->faker->text(),
            "photo_album" => $this->faker->imageUrl(332,168),
        ];
    }
}
