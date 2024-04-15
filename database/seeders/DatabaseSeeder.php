<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		User::factory()->create([
			'username'=> 'test',
			'email'   => 'test@example.com',
		]);

		Difficulty::factory()->create();
		$categories = Category::factory(15)->create();

		for ($i = 0; $i <= 10; $i++) {
			$quiz = Quiz::factory()->create();
			$categories[$i]->quizzes()->save($quiz);
			$categories[$i + 2]->quizzes()->save($quiz);

			for ($j = 0; $j <= 6; $j++) {
				$question = Question::factory()->create();
				Answer::factory(3)->create();
				if (!$question->answers->where('is_correct', true)->count()) {
					Answer::factory()->create(['is_correct' => true]);
				} else {
					Answer::factory()->create(['is_correct' => false]);
				}
			}

			if ($i % 3 === 0) {
				Result::factory()->create();
				Difficulty::factory()->create();
			}
		}
	}
}
