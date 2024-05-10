<?php

namespace App\Http\Controllers;

use App\Actions\CountCorrectAnswersAction;
use App\Http\Requests\TakeQuizRequest;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResultResource;
use App\Models\Quiz;
use App\Models\Result;
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
			->exclude($request->query('exclude'))
			->search($request->query('term'))
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

	public function take(TakeQuizRequest $request, Quiz $quiz, CountCorrectAnswersAction $countCorrectAnswersAction): QuizResultResource
	{
		if (auth()->id() && $quiz->results->firstWhere('user_id', auth()->id())) {
			return response()->json(['message' => 'You have already taken this quiz'], 403);
		}

		['time' => $time, 'answers' => $answers] = $request->validated();
		$quiz->load('questions.answers');

		['correctAnswers' => $correctAnswerCount, 'points' => $points] = $countCorrectAnswersAction->handle($quiz, $answers);

		Result::create([
			'quiz_id' => $quiz->id,
			'user_id' => auth()->id() ?? null,
			'points'  => $points,
			'time'    => $time,
		]);

		return new QuizResultResource((object)[
			'quiz'               => $quiz,
			'correctAnswerCount' => $correctAnswerCount,
			'time'               => $time,
		]);
	}
}
