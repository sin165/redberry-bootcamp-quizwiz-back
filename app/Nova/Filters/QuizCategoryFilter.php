<?php

namespace App\Nova\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizCategoryFilter extends BooleanFilter
{
	public function apply(NovaRequest $request, $query, $value): Builder
	{
		$ids = array_keys(array_filter($value));
		if (empty($ids)) {
			return $query;
		}
		return $query->whereHas('categories', function ($query) use ($ids) {
			$query->whereIn('categories.id', $ids);
		});
	}

	public function options(NovaRequest $request): array
	{
		return Category::pluck('id', 'name')->all();
	}
}
