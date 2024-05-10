<?php

namespace App\Actions;

use App\Models\Quiz;

class CountCorrectAnswersAction
{
	public function handle(Quiz $quiz, array $answers): array
	{
		$correctAnswers = 0;
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
				$correctAnswers++;
				$points += $question->points;
			}
		}
		return [
			'correctAnswers'     => $correctAnswers,
			'points'             => $points,
		];
	}
}
