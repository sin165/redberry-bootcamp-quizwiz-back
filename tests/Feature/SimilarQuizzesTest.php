<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimilarQuizzesTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed();
	}

	public function test_quizzes_share_at_least_one_category_with_the_original_quiz(): void
	{
		$quiz = Quiz::with('categories')->find(7);
		$categoryIds = $quiz->categories->pluck('id')->toArray();
		$response = $this->getJson(route('quizzes.index', [
			'per_page'   => 3,
			'status'     => 'not_completed',
			'exclude'    => 7,
			'categories' => implode(',', $categoryIds),
		]));
		$response->assertStatus(200);
		$quizzes = $response->json('data');
		foreach ($quizzes as $returnedQuiz) {
			$returnedQuizCategoryIds = array_column($returnedQuiz['categories'], 'id');
			$this->assertTrue(!empty(array_intersect($categoryIds, $returnedQuizCategoryIds)));
		}
	}

	public function test_original_quiz_not_included_in_returned_quizzes(): void
	{
		$quiz = Quiz::with('categories')->find(1);
		$categoryIds = $quiz->categories->pluck('id')->toArray();
		$response = $this->getJson(route('quizzes.index', [
			'per_page'   => 3,
			'status'     => 'not_completed',
			'exclude'    => 1,
			'categories' => implode(',', $categoryIds),
		]));
		$response->assertStatus(200);
		$quizzes = $response->json('data');
		foreach ($quizzes as $returnedQuiz) {
			$this->assertNotEquals(1, $returnedQuiz['id']);
		}
	}

	public function test_no_completed_quizzes_returned_when_user_is_logged_in(): void
	{
		$quiz = Quiz::find(1);
		$categoryIds = $quiz->categories->pluck('id')->toArray();

		$quizTwo = Quiz::find(2);
		if (empty(array_intersect($quizTwo->categories->pluck('id')->toArray(), $quiz->categories->pluck('id')->toArray()))) {
			$quizTwo->categories()->attach($quiz->categories);
		}
		Result::create(['quiz_id' => 2, 'user_id' => 1, 'time' => 1, 'points' => 1]);

		$user = User::find(1);
		$response = $this
			->actingAs($user)
			->getJson(route('quizzes.index', [
				'per_page'   => 3,
				'status'     => 'not_completed',
				'exclude'    => 1,
				'categories' => implode(',', $categoryIds),
			]));
		$response->assertStatus(200);

		$quizzes = $response->json('data');
		foreach ($quizzes as $returnedQuiz) {
			$result = Result::all()->where('user_id', $user->id)->where('quiz_id', $returnedQuiz['id']);
			$this->assertEmpty($result);
		}
	}
}
