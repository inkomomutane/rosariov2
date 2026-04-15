<?php

namespace App\Http\Controllers\MemberInfo\Profile;

use App\Models\User;

class ShowMemberBaseInfo
{
    public function __invoke(User $member)
    {
        return inertia('MemberInfo/Members/Edit',[
            'member' => $member
        ]);
    }
}
