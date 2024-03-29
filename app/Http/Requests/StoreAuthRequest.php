<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'username'     => 'required|min:3',
			'email'        => 'required|email',
			'password'     => 'required|min:3',
			'confirmation' => 'required|same:password',
			'terms'        => 'required|accepted',
		];
	}
}
