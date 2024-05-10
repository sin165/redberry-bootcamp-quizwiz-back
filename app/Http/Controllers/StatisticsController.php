<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;

class StatisticsController extends Controller
{
	public function getCounts()
	{
		return response()->json([
			'quiz_count'     => Quiz::count(),
			'category_count' => Category::count(),
		]);
	}
}
