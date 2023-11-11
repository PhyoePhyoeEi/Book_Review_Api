<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

            $user_id = User::inRandomOrder()->value('id');
            return [
                'title' => $this->faker->sentence,
                'author' => $this->faker->name,
                'published_year' => $this->faker->year,
                'description' => $this->faker->paragraph,
                'user_id' => $user_id,
            ];

    }
}
