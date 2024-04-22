<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuizController extends Controller
{
	public function index(Request $request): AnonymousResourceCollection
	{
		$perPage = $request->query('per_page', 9);
		$quizzes = Quiz::with('difficulty', 'categories', 'questions', 'results')
			->simplePaginate($perPage)
			->withQueryString();
		return QuizResource::collection($quizzes);
	}

	public function show(Quiz $quiz): QuizResource
	{
		$quiz->load('questions.answers');
		return new QuizResource($quiz);
	}
}
