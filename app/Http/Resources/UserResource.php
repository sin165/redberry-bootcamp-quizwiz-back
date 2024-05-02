<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	public static $wrap = null;

	public function toArray(Request $request): array
	{
		return [
			'id'       => $this->id,
			'username' => $this->username,
			'email'    => $this->email,
			'avatar'   => $this->when($this->avatar, asset('storage/' . $this->avatar)),
		];
	}
}
