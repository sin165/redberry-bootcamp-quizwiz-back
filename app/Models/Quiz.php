<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	use HasFactory;

	protected $guarded = [];

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class);
	}

	public function difficulty(): BelongsTo
	{
		return $this->belongsTo(Difficulty::class);
	}

	public function questions(): HasMany
	{
		return $this->hasMany(Question::class);
	}

	public function results(): HasMany
	{
		return $this->hasMany(Result::class);
	}

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'results')->withPivot('points', 'time');
	}

	public function scopeFilterDifficulties(Builder $query, ?string $difficulties): Builder
	{
		if ($difficulties) {
			$query->whereIn('difficulty_id', explode(',', $difficulties));
		}
		return $query;
	}

	public function scopeFilterCategories(Builder $query, ?string $categories): Builder
	{
		if ($categories) {
			$query->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('categories.id', explode(',', $categories));
			});
		}
		return $query;
	}

	public function scopeFilterCompletion(Builder $query, ?string $status): Builder
	{
		if (auth()->user()) {
			if ($status === 'completed') {
				$query->whereHas('users', function ($query) {
					$query->where('users.id', auth()->id());
				});
			}
			if ($status === 'not_completed') {
				$query->whereDoesntHave('users', function ($query) {
					$query->where('users.id', auth()->id());
				});
			}
		}
		return $query;
	}

	public function scopeSort(Builder $query, ?string $sort): Builder
	{
		if ($sort === 'a-z') {
			$query->orderBy('title', 'asc');
		} elseif ($sort === 'z-a') {
			$query->orderBy('title', 'desc');
		} elseif ($sort === 'newest') {
			$query->orderBy('created_at', 'desc');
		} elseif ($sort === 'oldest') {
			$query->orderBy('created_at', 'asc');
		} elseif ($sort === 'popular') {
			$query->withCount('results')->orderBy('results_count', 'desc');
		}
		return $query;
	}
}
