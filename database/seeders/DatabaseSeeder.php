<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$this->call([
			UserSeeder::class,
			DifficultySeeder::class,
			CategorySeeder::class,
		]);
		$categories = Category::all();

		Quiz::factory(10)->create();

		Quiz::all()->each(function ($quiz) use ($categories) {
			$selectedCategories = $categories->random(rand(1, 3));
			$quiz->categories()->attach($selectedCategories);
			Question::factory(rand(3, 9))->create(['quiz_id' => $quiz->id]);
		});

		Question::all()->each(function ($question) {
			Answer::factory(rand(2, 4))->create(['question_id' => $question->id]);
			if (!$question->answers->where('is_correct', true)->count()) {
				Answer::factory()->create(['question_id' => $question->id, 'is_correct' => true]);
			} else {
				Answer::factory()->create(['question_id' => $question->id, 'is_correct' => false]);
			}
		});
	}
}
