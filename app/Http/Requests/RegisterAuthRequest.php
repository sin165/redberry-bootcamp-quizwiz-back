<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'username'     => 'required|min:3|unique:users',
			'email'        => 'required|email|unique:users',
			'password'     => 'required|min:3',
			'confirmation' => 'required|same:password',
			'terms'        => 'required|accepted',
		];
	}
}
