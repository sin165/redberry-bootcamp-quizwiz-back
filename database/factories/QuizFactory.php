<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Difficulty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title'         => fake()->sentence(),
			'instructions'  => fake()->paragraph(),
			'difficulty_id' => Difficulty::latest('id')->first()->id,
			'time_limit'    => fake()->numberBetween(1, 10) * 60,
		];
	}
}
