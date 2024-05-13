<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		Nova::createUserUsing(function ($command) {
			return [
				$command->ask('Username'),
				$command->ask('Email Address'),
				$command->secret('Password'),
			];
		}, function ($username, $email, $password) {
			(new User)->forceFill([
				'username' => $username,
				'email'    => $email,
				'password' => $password,
			])->save();
		});
	}

	/**
	 * Register the Nova routes.
	 *
	 * @return void
	 */
	protected function routes()
	{
		Nova::routes()
				->withAuthenticationRoutes()
				->withPasswordResetRoutes()
				->register();
	}

	/**
	 * Register the Nova gate.
	 *
	 * This gate determines who can access Nova in non-local environments.
	 *
	 * @return void
	 */
	protected function gate()
	{
		Gate::define('viewNova', function ($user) {
			return in_array($user->email, [
				'test@example.com',
			]);
		});
	}

	/**
	 * Get the dashboards that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	protected function dashboards()
	{
		return [
			new \App\Nova\Dashboards\Main,
		];
	}

	/**
	 * Get the tools that should be listed in the Nova sidebar.
	 *
	 * @return array
	 */
	public function tools()
	{
		return [];
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
	}
}
