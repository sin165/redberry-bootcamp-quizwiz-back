<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function store(StoreAuthRequest $request): JsonResponse
	{
		return response()->json($request->validated());
	}
}
