<?php

namespace App\Nova;

use App\Nova\Filters\QuizCategoryFilter;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quiz extends Resource
{
	public static $model = \App\Models\Quiz::class;

	public static $title = 'title';

	public static $search = [
		'id', 'title',
	];

	public static $with = ['difficulty'];

	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Image::make('picture')->nullable(),

			Text::make('title')
				->sortable()
				->rules('required', 'unique:quizzes,title,{{resourceId}}'),

			Textarea::make('description')
				->alwaysShow()
				->rules('required'),

			Textarea::make('instructions')
				->alwaysShow()
				->rules('required'),

			Number::make('Time limit')
				->sortable()
				->rules('required')
				->min(0)
				->help('seconds'),

			BelongsTo::make('Difficulty')
				->showCreateRelationButton()
				->dontReorderAssociatables()
				->sortable()
				->rules('required'),

			BelongsToMany::make('Categories')
				->showCreateRelationButton(),

			HasMany::make('Questions'),

			HasMany::make('Results'),
		];
	}

	public function cards(NovaRequest $request): array
	{
		return [];
	}

	public function filters(NovaRequest $request): array
	{
		return [
			new QuizCategoryFilter(),
		];
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
