<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
	use RefreshDatabase;

	public function test_user_added_in_the_database_when_all_inputs_provided_correctly(): void
	{
		$this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$this->assertDatabaseHas('users', [
			'email' => 'someemail@example.com',
		]);
	}

	public function test_verification_email_sent_when_all_inputs_provided_correctly(): void
	{
		Notification::fake();

		$this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$user = User::firstWhere('email', 'someemail@example.com');

		Notification::assertSentTo($user, VerifyEmail::class);
	}

	public function test_register_route_returns_created_when_all_inputs_provided_correctly(): void
	{
		$response = $this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$response->assertStatus(201);
		$response->assertSee('User registered successfully');
	}

	public function test_register_route_returns_unprocessable_content_error_when_username_exists(): void
	{
		User::factory()->create([
			'username'=> 'somename',
			'email'   => 'someemail@example.com',
		]);

		$response = $this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail2@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$response->assertStatus(422);
		$response->assertSee('The username has already been taken');
		$this->assertDatabaseMissing('users', [
			'email' => 'someemail2@example.com',
		]);
	}

	public function test_register_route_returns_unprocessable_content_error_when_email_exists(): void
	{
		User::factory()->create([
			'username'=> 'somename',
			'email'   => 'someemail@example.com',
		]);

		$response = $this->postJson('/api/register', [
			'username'     => 'somename2',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$response->assertStatus(422);
		$response->assertSee('The email has already been taken');
		$this->assertDatabaseMissing('users', [
			'username' => 'somename2',
		]);
	}

	public function test_register_route_returns_unprocessable_content_error_when_invalid_email_is_provided(): void
	{
		$response = $this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someinvalidemail',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => true,
		]);

		$response->assertStatus(422);
		$response->assertSee('must be a valid email');
		$this->assertDatabaseMissing('users', [
			'username' => 'somename',
		]);
	}

	public function test_register_route_returns_unprocessable_content_error_when_passwords_dont_match(): void
	{
		$response = $this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'notthesamepassword',
			'terms'        => true,
		]);

		$response->assertStatus(422);
		$response->assertSee('confirmation field must match password');
		$this->assertDatabaseMissing('users', [
			'username' => 'somename',
		]);
	}

	public function test_register_route_returns_unprocessable_content_error_when_terms_not_accepted(): void
	{
		$response = $this->postJson('/api/register', [
			'username'     => 'somename',
			'email'        => 'someemail@example.com',
			'password'     => 'somepassword',
			'confirmation' => 'somepassword',
			'terms'        => 'blurrg',
		]);

		$response->assertStatus(422);
		$response->assertSee('must be accepted');
		$this->assertDatabaseMissing('users', [
			'username' => 'somename',
		]);
	}

	public function test_register_route_returns_unprocessable_content_error_when_inputs_not_provided(): void
	{
		$response = $this->postJson('/api/register');

		$response->assertStatus(422);
		$response->assertJson(
			fn (AssertableJson $json) => $json
				->hasAll(['errors.username', 'errors.email', 'errors.password', 'errors.confirmation', 'errors.terms'])
				->etc()
		);
	}

	public function test_register_route_returns_forbidden_status_when_already_logged_in(): void
	{
		$user = User::factory()->create();

		$response = $this
			->actingAs($user)
			->postJson('/api/register', [
				'username'     => 'somename',
				'email'        => 'someemail@example.com',
				'password'     => 'somepassword',
				'confirmation' => 'somepassword',
				'terms'        => true,
			]);

		$response->assertStatus(403);
		$response->assertSee('You must not be logged');
	}

	public function test_user_can_verify_email(): void
	{
		$user = User::factory()->create(['email_verified_at' => null]);

		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(Config::get('auth.verification.expire', 120)),
			[
				'id'   => $user->getKey(),
				'hash' => sha1($user->getEmailForVerification()),
			]
		);

		$response = $this->get($verificationUrl);
		$response->assertSee('Email verified successfully');
		$user->refresh();
		$this->assertNotNull($user->email_verified_at);
	}

	public function test_user_can_not_verify_email_when_email_expired(): void
	{
		$user = User::factory()->create(['email_verified_at' => null]);

		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->subMinutes(1),
			[
				'id'   => $user->getKey(),
				'hash' => sha1($user->getEmailForVerification()),
			]
		);

		$response = $this->get($verificationUrl);
		$response->assertStatus(403);
		$user->refresh();
		$this->assertNull($user->email_verified_at);
	}
}
