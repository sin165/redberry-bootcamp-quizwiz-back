<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use Illuminate\Http\JsonResponse;

class DifficultyController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(Difficulty::all());
	}
}
