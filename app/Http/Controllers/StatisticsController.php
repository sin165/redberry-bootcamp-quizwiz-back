<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
	public function getCounts(): JsonResponse
	{
		return response()->json([
			'quiz_count'     => Quiz::count(),
			'category_count' => Category::count(),
		]);
	}
}
