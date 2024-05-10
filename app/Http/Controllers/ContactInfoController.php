<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactInfoResource;
use App\Models\ContactInfo;

class ContactInfoController extends Controller
{
	public function getInfo(): ContactInfoResource
	{
		return new ContactInfoResource(ContactInfo::latest('id')->first());
	}
}
