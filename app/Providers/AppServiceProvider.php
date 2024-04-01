<?php

namespace App\Providers;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		VerifyEmail::createUrlUsing(function ($notifiable): string {
			$frontendUrl = Config::get('app.frontend_url');

			$verifyUrl = URL::temporarySignedRoute(
				'verification.verify',
				now()->addMinutes(Config::get('auth.verification.expire', 120)),
				[
					'id'   => $notifiable->getKey(),
					'hash' => sha1($notifiable->getEmailForVerification()),
				]
			);

			return $frontendUrl . '/login?verify_url=' . urlencode($verifyUrl);
		});

		VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
			return (new MailMessage)->subject('Please verify your email')->view(
				['email-verification', 'email-verification-plain'],
				['url' => $url, 'logo' => asset('images/logo.png'), 'username' => $notifiable->username]
			);
		});
	}
}
