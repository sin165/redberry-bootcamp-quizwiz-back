<?php

namespace App\Http\Controllers;

use App\Http\Requests\TakeQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$perPage = $request->query('per_page', 9);
		$quizzes = Quiz::with('difficulty', 'categories', 'questions', 'results')
			->filterDifficulties($request->query('difficulties'))
			->filterCategories($request->query('categories'))
			->filterCompletion($request->query('status'))
			->sort($request->query('sort'))
			->simplePaginate($perPage)
			->withQueryString();
		return QuizResource::collection($quizzes);
	}

	public function show(Quiz $quiz): QuizResource
	{
		$quiz->load('questions.answers');
		return new QuizResource($quiz);
	}

	public function take(TakeQuizRequest $request, Quiz $quiz): JsonResponse
	{
		if (auth()->id() && $quiz->results->firstWhere('user_id', auth()->id())) {
			return response()->json(['message' => 'You have already taken this quiz'], 403);
		}

		['time' => $time, 'answers' => $answers] = $request->validated();
		$quiz->load('questions.answers');

		$correctAnswerCount = 0;
		$points = 0;
		foreach ($quiz->questions as $question) {
			$correct = true;
			foreach ($question->answers as $answer) {
				if (in_array($answer->id, $answers) != $answer->is_correct) {
					$correct = false;
					break;
				}
			}
			if ($correct) {
				$correctAnswerCount++;
				$points += $question->points;
			}
		}

		Result::create([
			'quiz_id' => $quiz->id,
			'user_id' => auth()->id() ?? null,
			'points'  => $points,
			'time'    => $time,
		]);

		return response()->json([
			'quiz_name'         => $quiz->title,
			'quiz_level'        => $quiz->difficulty->name,
			'correct_answers'   => $correctAnswerCount,
			'incorrect_answers' => $quiz->questions->count() - $correctAnswerCount,
			'time'              => $time,
		]);
	}
}
