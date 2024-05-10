<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResultResource extends JsonResource
{
	public static $wrap = null;

	public function toArray(Request $request): array
	{
		return [
			'quiz_name'         => $this->quiz->title,
			'quiz_level'        => $this->quiz->difficulty->name,
			'quiz_level_color'  => $this->quiz->difficulty->text_color,
			'correct_answers'   => $this->correctAnswerCount,
			'incorrect_answers' => $this->quiz->questions->count() - $this->correctAnswerCount,
			'time'              => $this->time,
		];
	}
}
