<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'              => $this->id,
			'text'            => $this->text,
			'points'          => $this->points,
			'answers'         => AnswerResource::collection($this->answers),
			'correct_answers' => $this->answers->sum('is_correct'),
		];
	}
}
