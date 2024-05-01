<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Question extends Resource
{
	public static $model = \App\Models\Question::class;

	public static $title = 'text';

	public static $search = [
		'id', 'text',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			BelongsTo::make('Quiz')
				->sortable()
				->dontReorderAssociatables(),

			Text::make('Text')
				->rules('required'),

			Number::make('Points')
				->rules('required'),

			HasMany::make('Answers'),
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
