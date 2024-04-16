<?php

namespace App\Models;

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
		return $this->belongsToMany(User::class, 'results');
	}
}
