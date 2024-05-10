<?php

namespace App\Nova;

use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
	public static $model = \App\Models\User::class;

	public static $title = 'username';

	public static $search = [
		'id', 'username', 'email',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Image::make('avatar')->nullable()->rounded(),

			Text::make('Username', 'username')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}'),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', Rules\Password::defaults())
				->updateRules('nullable', Rules\Password::defaults()),

			HasMany::make('Completed quizzes', 'results', 'App\Nova\Result'),
		];
	}

	public function cards(NovaRequest $request): array
	{
		return [];
	}

	public function filters(NovaRequest $request): array
	{
		return [];
	}

	public function lenses(NovaRequest $request): array
	{
		return [];
	}

	public function actions(NovaRequest $request): array
	{
		return [];
	}
}