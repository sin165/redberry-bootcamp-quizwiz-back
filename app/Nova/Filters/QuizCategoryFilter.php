<?php

namespace App\Nova\Filters;

use App\Models\Category;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizCategoryFilter extends BooleanFilter
{
	/**
	 * Apply the filter to the given query.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 * @param \Illuminate\Database\Eloquent\Builder   $query
	 * @param mixed                                   $value
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function apply(NovaRequest $request, $query, $value)
	{
		$ids = array_keys(array_filter($value));
		if (empty($ids)) {
			return $query;
		}
		return $query->whereHas('categories', function ($query) use ($ids) {
			$query->whereIn('categories.id', $ids);
		});
	}

	/**
	 * Get the filter's available options.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return array
	 */
	public function options(NovaRequest $request)
	{
		return Category::pluck('id', 'name')->all();
	}
}
