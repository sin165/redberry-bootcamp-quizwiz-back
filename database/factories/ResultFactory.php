<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Quiz;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'quiz_id' => Quiz::latest('id')->first()->id,
			'user_id' => User::latest('id')->first()->id,
			'points'  => fake()->numberBetween(0, 6),
			'time'    => fake()->numberBetween(1, Quiz::latest('id')->first()->time_limit),
		];
	}
}
