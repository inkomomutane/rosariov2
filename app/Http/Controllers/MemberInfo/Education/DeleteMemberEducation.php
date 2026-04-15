<?php

namespace App\Http\Controllers\MemberInfo\Education;

use App\Models\Education;
use App\Models\User;

class DeleteMemberEducation
{
    public function __invoke(User $member, Education $education)
    {
        abort_if(!auth()->user()->is_admin, 403, 'You are not authorized to view this page.');
        $education->delete();
        flash()->addSuccess(__('Education deleted successfully'));
        return inertia_location(route('member.education.list', $member->id));
    }
}
