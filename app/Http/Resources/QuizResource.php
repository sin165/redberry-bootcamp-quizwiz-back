<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                => $this->id,
			'title'             => $this->title,
			'instructions'      => $this->instructions,
			'time_limit'        => $this->time_limit,
			'auth_user_result'  => $this->when(auth()->user(), new ResultResource($this->results->firstWhere('user_id', auth()->id()))),
			'max_points'        => $this->questions->sum('points'),
			'results_count'     => $this->results->count(),
			'categories'        => CategoryResource::collection($this->categories),
			'difficulty'        => $this->difficulty,
			'questions'         => $this->when(Route::currentRouteName() === 'quizzes.show', QuestionResource::collection($this->questions)),
		];
	}
}
