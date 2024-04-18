<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
			'auth_user_result'  => $this->when(auth()->user(), $this->results->firstWhere('user_id', auth()->id())),
			'max_points'        => $this->questions->sum('points'),
			'results_count'     => $this->results->count(),
			'categories'        => $this->categories,
		];
	}
}
