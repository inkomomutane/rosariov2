<?php

namespace App\Http\Controllers\MemberInfo\PoliceCard;

use App\Data\UserDto;
use App\Models\User;

class ShowPoliceCard
{
    public function __invoke(User $member)
    {
        return inertia('MemberInfo/PoliceCard/Show', [
            'member' => UserDto::fromModel($member),
        ]);
    }
}
