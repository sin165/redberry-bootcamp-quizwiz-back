<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TakeQuizRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'time'    => 'required|integer',
			'answers' => 'required|array',
		];
	}
}
