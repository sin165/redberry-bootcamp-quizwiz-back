<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactInfoResource extends JsonResource
{
	public static $wrap = null;

	public function toArray(Request $request): array
	{
		return [
			'email'    => $this->email,
			'tel'      => $this->tel,
			'facebook' => $this->facebook,
			'linkedin' => $this->linkedin,
		];
	}
}
