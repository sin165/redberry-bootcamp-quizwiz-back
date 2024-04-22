<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'         => $this->id,
			'created_at' => $this->created_at->format('d M, Y'),
			'points'     => $this->points,
			'time'       => ceil($this->time / 60) . ' Min',
		];
	}
}
