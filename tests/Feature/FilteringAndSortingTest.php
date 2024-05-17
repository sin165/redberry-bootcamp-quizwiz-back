<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilteringAndSortingTest extends TestCase
{
	use RefreshDatabase;

	public function setUp(): void
	{
		parent::setUp();
		$this->seed();
	}

	public function test_first_9_quizzes_returned_when_no_query_parameters_provided(): void
	{
		$response = $this->getJson(route('quizzes.index'));
		$response
			->assertStatus(200)
			->assertJsonCount(9, 'data')
			->assertJsonPath('data.0.id', 1)
			->assertJsonPath('data.8.id', 9);
	}

	public function test_only_quizzes_of_selected_difficulties_returned_when_difficulties_parameter_provided(): void
	{
		$response = $this->get(route('quizzes.index', ['difficulties' => '3,5']));
		$response->assertStatus(200);
		$data = $response->json('data');
		foreach ($data as $quiz) {
			$this->assertTrue(in_array($quiz['difficulty']['id'], [3, 5]));
		}
	}

	public function test_only_quizzes_of_selected_categories_returned_when_categories_parameter_provided(): void
	{
		$response = $this->get(route('quizzes.index', ['categories' => '2,7']));
		$response->assertStatus(200);
		$data = $response->json('data');
		foreach ($data as $quiz) {
			$categoryIds = array_column($quiz['categories'], 'id');
			$this->assertTrue(!empty(array_intersect($categoryIds, [2, 7])));
		}
	}

	public function test_only_completed_quizzes_returned_when_status_parameter_equals_completed(): void
	{
		Result::create(['quiz_id' => 2, 'user_id' => 1, 'time' => 1, 'points' => 1]);
		Result::create(['quiz_id' => 8, 'user_id' => 1, 'time' => 1, 'points' => 1]);
		$user = User::first();

		$response = $this->actingAs($user)->get(route('quizzes.index', ['status' => 'completed']));
		$response->assertStatus(200);

		$data = $response->json('data');
		foreach ($data as $quiz) {
			$result = Result::all()->where('user_id', $user->id)->where('quiz_id', $quiz['id']);
			$this->assertNotEmpty($result);
		}
	}

	public function test_quizzes_returned_contain_the_search_term_when_term_parameter_provided(): void
	{
		$response = $this->get(route('quizzes.index', ['term' => 'qui']));
		$response->assertStatus(200);
		$data = $response->json('data');
		foreach ($data as $quiz) {
			$this->assertStringContainsString('qui', strtolower($quiz['title']));
		}
	}

	public function test_quizzes_returned_are_sorted_newest_first_when_sort_parameter_equals_newest(): void
	{
		$quiz = Quiz::find(6);
		$quiz->created_at = strtotime('+1 hour');
		$quiz->save();
		$quiz2 = Quiz::find(10);
		$quiz2->created_at = strtotime('+2 hours');
		$quiz2->save();

		$response = $this->get(route('quizzes.index', ['sort' => 'newest']));
		$response
			->assertStatus(200)
			->assertJsonPath('data.0.id', 10)
			->assertJsonPath('data.1.id', 6);
	}

	public function test_quizzes_returned_are_sorted_by_popularity_when_sort_parameter_equals_popular(): void
	{
		User::factory()->create();
		Result::create(['quiz_id' => 7, 'user_id' => 1, 'time' => 1, 'points' => 1]);
		Result::create(['quiz_id' => 7, 'user_id' => 2, 'time' => 1, 'points' => 1]);
		Result::create(['quiz_id' => 4, 'user_id' => 1, 'time' => 1, 'points' => 1]);

		$response = $this->get(route('quizzes.index', ['sort' => 'popular']));
		$response
			->assertStatus(200)
			->assertJsonPath('data.0.id', 7)
			->assertJsonPath('data.1.id', 4);
	}

	public function test_quizzes_returned_are_filtered_and_sorted_correctly_when_multiple_filter_and_sort_parameters_provided(): void
	{
		$response = $this->get(route('quizzes.index', [
			'difficulties' => '2,3,4,5',
			'categories'   => '2,4,6,7,10,13',
			'sort'         => 'a-z',
		]));

		$data = $response->json('data');
		foreach ($data as $quiz) {
			$this->assertTrue(in_array($quiz['difficulty']['id'], [2, 3, 4, 5]));
			$categoryIds = array_column($quiz['categories'], 'id');
			$this->assertTrue(!empty(array_intersect($categoryIds, [2, 4, 6, 7, 10, 13])));
		}

		$titles = array_column($data, 'title');
		$sortedTitles = $titles;
		sort($sortedTitles);
		$this->assertEquals($sortedTitles, $titles);
	}
}
