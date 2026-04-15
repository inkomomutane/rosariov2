<?php

namespace App\Http\Controllers\MemberInfo\Experience;

use App\Models\Experience;
use App\Models\User;

class DeleteMemberExperience
{
    public function __invoke(User $member, Experience $experience)
    {
        $experience->delete();
        flash()->addSuccess(__('Experience deleted successfully'));
        return inertia_location(route('member.experience.list', $member->id));
    }
}
