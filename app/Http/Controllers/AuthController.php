<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResendEmailRequest;
use App\Models\User;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		event(new Registered($user));
		return response()->json(['message' => 'User registered successfully'], 201);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$data = $request->validated();

		$user = User::where('email', $data['email'])->first();
		if ($user && !$user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Email not verified', 'email' => $data['email']], 403);
		}

		if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'])) {
			$request->session()->regenerate();
			$user = Auth::user();
			return response()->json([
				'message' => 'Login successful',
				'user'    => [
					'id'       => $user->id,
					'username' => $user->username,
					'email'    => $user->email,
				],
			], 200);
		}
		return response()->json(['message' => 'Login failed'], 401);
	}

	public function verifyEmail(Request $request): JsonResponse
	{
		$user = User::findOrFail($request->id);
		if ($user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Email already verified']);
		}
		$user->markEmailAsVerified();
		event(new Verified($user));
		return response()->json(['message' => 'Email verified successfully']);
	}

	public function resendEmail(ResendEmailRequest $request): JsonResponse
	{
		if ($request->has('id')) {
			$user = User::findOrFail($request->validated()['id']);
		} else {
			$user = User::where('email', $request->validated()['email'])->first();
		}
		$user->sendEmailVerificationNotification();
		return response()->json(['message' => 'Verification link sent!']);
	}
}
