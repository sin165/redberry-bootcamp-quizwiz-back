<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SetNovaSessionCookie
{
	public function handle($request, Closure $next)
	{
		Config::set('session.cookie', Str::slug(config('app.name'), '_') . '_nova_session');
		return $next($request);
	}
}
