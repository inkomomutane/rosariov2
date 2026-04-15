<?php

namespace App\Http\Controllers\Profile;

use App\Data\PatentData;
use App\Data\UserDto;
use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\Patent;
use Inertia\Inertia;

class ProfileInfoController
{
    public function __invoke()
    {
        return Inertia::render('Profile/Index', [
            'user' => UserDto::from( auth()->user()->toArray()),
            'sex' => Sex::toValues(),
            'marital_status' => MaritalStatus::toValues(),
            'patents' => PatentData::collect(Patent::all())
        ]);
    }
}
