<?php

namespace App\Nova;

use Laravel\Nova\Fields\Color;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Difficulty extends Resource
{
	public static $model = \App\Models\Difficulty::class;

	public static $title = 'name';

	public static $search = [
		'id', 'name',
	];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Text::make('Name')
				->rules('required', 'unique:difficulties,name,{{resourceId}}'),

			Color::make('Text color')
				->rules('required'),

			Color::make('Background color', 'bg_color')
				->rules('required'),
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
